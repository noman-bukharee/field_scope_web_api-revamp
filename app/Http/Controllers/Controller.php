<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ApiAuth;
use App\Libraries\readXlsx;
use App\Models\MailTemplate;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use MongoDB\Driver\Session;
use Request;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public
        $__params       = [],
        $__is_ajax      = false,
        $__is_api_call  = false,
        $__is_redirect  = false,
        $__is_error     = false,
        $__is_paginate  = true, // to control pagination object
        $__is_collection= true, // for item detail response
        $__collection   = true, // to control general response
        $__is_custom_collection = false, // to control custom collection resource
        $__module       = '',
        $__view         = '',
        $__flash_class  = ''; // danger , info , success

    const ERROR_CODES = [
        'error' => '0400',
        'info' => '0300',
        'success' => '0200',
    ];

    public
        $call_mode      = 'admin';    // api, admin, web

    function __construct(){
        //echo \Route::getCurrentRoute()->getActionName();

        $this->_callSetup();
    }

    private function _callSetup()
    {
        $this->__call_mode   = 'web';
        if (preg_match('#/api/#', Request::url())) {

            $this->__is_api_call = true;
            $this->call_mode   = 'api';
        }

        if (preg_match('/admin/', Request::url())) {
            $this->call_mode   = 'admin';
        }

        if($this->__is_api_call) {
            $this->middleware(ApiAuth::class);
        }
    }

    protected function __validateRequestParams($input_params, $param_rules, $customMessages = [])
    {
        $this->__params = $input_params;
        $validator = \Validator::make($input_params, $param_rules, $customMessages);

        $errors = [];

        if($validator->fails()){
            foreach ($param_rules as $field => $value){
                $message = $validator->errors()->first($field);
                if(!empty($message)) {
                    $errors[$field] = $message;
                }
            }
            $this->__is_error = true;

            if($this->__is_api_call)
                return $this->__sendError(__('app.validation_error'), $errors,400);

            if($this->__is_ajax)
                return $this->__sendError(__('app.validation_error'), $errors,400);

            if($this->__is_redirect) {
                return $this->__sendError(__('app.validation_error'), $errors);

//                return redirect(\URL::to($this->__module . $this->__view));
            }

            return View::make($this->__module.$this->__view, ['error' => $this->__sendError(__('app.validation_error'), $errors), 'page' => $this->__view]);
            print_r($errors);exit;
            return $errors;
        }
        return $response = [
            'code' => 200,
            'success' => true,
            'message' => 'success',
        ];
    }

    protected function __sendResponse($resource, $obj_model, $response_code, $message)
    {
//        dd(
//            "Collection",$obj_model instanceof Collection,
//            "Model",$obj_model instanceof Model,
//            "LengthAwarePaginator",$obj_model instanceof LengthAwarePaginator,
//            "Arr",is_array($obj_model)
//        );

        if($obj_model instanceof Collection){
            $this->__collection = true;
            $this->__is_collection = true;
            $this->__is_paginate = false;
        }else if ($obj_model instanceof Model){
            $this->__collection = true;
            $this->__is_collection = false;
            $this->__is_paginate = false;
        } elseif ($obj_model instanceof LengthAwarePaginator) {
            $this->__collection = true;
            $this->__is_collection = true;
            $this->__is_paginate = true;
        } else if (is_array($obj_model)) {
            $this->__collection = false;
            $this->__is_collection = false;
            $this->__is_paginate = false;
        }

        // __is_collection == true >> multiple records
        // __collection == true >> use API resource
        if($this->__is_collection){
            $page_info = $this->__getPaginate($obj_model);
        }

        $resource = "\App\Http\Resources\\$resource";
        if($this->__is_custom_collection){
            $this->__collection = false;
            $this->__is_collection = false;

            $custom_resource_obj = new $resource();
            $collection = [];
            foreach($obj_model as $row) {
                $collection[] = $custom_resource_obj->toArray($row);
            }
            $obj_model = [];
            $obj_model = $collection;
            if(count(((array) $obj_model)) < 1 ){
                $message = 'No data found.';
                $response_code = 204;
            }
        }

        // api-resource and multiple records
        if($this->__collection && $this->__is_collection) {
            $result = $resource::collection($obj_model);
            // when data record set is empty
            if($this->__collection && $result->isEmpty()){
                $message = 'No data found.';
                $response_code = 204;
            }
        }

        // api-resource and single records
        else if($this->__collection && !$this->__is_collection) {
            $result = new $resource($obj_model);
            // when data record set is empty
            if ($this->__collection && !$result->exists) {
                $message = 'No data found.';
                $response_code = 204;
            }
        }

        if(!$this->__collection) {
            $result = $obj_model;
        }

        $response = [
            'code'    => $response_code,
            //'success' => true,
            'data'    => ($this->__collection)? $result : $obj_model,
            'message' => $message,
            'links' => $page_info['links'],
            'meta' => $page_info['meta'],
            'draw' => !empty(\Request::input('draw')) ? ( \Request::input('draw') + 1 ) :0,
            'recordsTotal' => $page_info['meta']['total'],
            'recordsFiltered' => $page_info['meta']['total'],
        ];

        if ($this->__is_ajax || $this->__is_api_call) {
            return response()->json($response, 200);
        }


        if($this->__is_redirect)
            return redirect(\URL::to($this->__module.$this->__view));


        $data = isset($result->collection)? json_decode($result->collection): $result;
        return View::make($this->__module.$this->__view, ['data' => $data, 'page' => $this->__view]);

        //print 'html response';
        //exit;
    }

    protected function __getPaginate($obj_model)
    {
        if(!$this->__is_paginate){
            $response['links'] = [
                "first" => null,
                "last" => null,
                "prev" =>  null,
                "next" =>  null
            ];

//            is_object($obj_model) ? (method_exists($obj_model, 'count')) ? $obj_model->count() : 1 : count($obj_model)

            $total = null;
            if (method_exists($obj_model, 'count')) {
                $total = $obj_model->count();
            } else if (!empty($obj_model)) {
                $total = count($obj_model);
            }


            $response['meta'] = [
                "current_page" =>  1,
                "from" =>  1,
                "last_page" =>  0,
                "to" =>  0,
//                "total" =>  is_object($obj_model) ? (method_exists($obj_model, 'count')) ? $obj_model->count() : 1 : isset($obj_model) ? count ((array) $obj_model) : NULL
//                "total" =>  count ((array) $obj_model)
                 "total" =>  $total
            ];

            return $response;
        } else {

        $response['links'] = [
            "first" => $obj_model->url($obj_model->firstItem()),
            "last" => $obj_model->url($obj_model->lastPage()),
            "prev" =>  $obj_model->previousPageUrl(),
            "next" =>  $obj_model->nextPageUrl()
        ];

        $response['meta'] = [
            "current_page" =>  $obj_model->currentPage(),
            "from" =>  $obj_model->firstItem(),
            "last_page" =>  $obj_model->lastPage(),
            //"path" =>  $obj_model->url(),
            //"per_page" =>  $obj_model->perPage(),
            "to" =>  $obj_model->lastItem(),
            "total" =>  $obj_model->total()
        ];
        }
        return $response;
    }

    public function __sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'code' => $code,
        //    'success' => false,
            'data' => [],
            'message' => $error,
        ];

//        \Log::debug("__sendError",[
//            '_is_api_call' => $this->__is_api_call,
//            '_is_ajax' => $this->__is_ajax,
//            '_is_redirect' => $this->__is_redirect,
//            'errorMessages' => $errorMessages,
//        ]);

        if(!empty($errorMessages))
            $response['data'] = $errorMessages;

        if($this->__is_api_call)
            return response()->json($response, $code);

        if($this->__is_ajax)
            return response()->json($response, $code);

        if($this->__is_redirect) {
            $request = Request();

            $request->session()->flash('code' , $code);
            $request->session()->flash('class' , !empty($this->__flash_class) ? $this->__flash_class : 'danger');
            $request->session()->flash('message' , $error);
            $request->session()->flash('data' , !empty($errorMessages) ? $errorMessages : []);

            return redirect(\URL::to($this->__module . $this->__view));
        }

        return View::make($this->__module.$this->__view, ['error' => $response, 'page' => $this->__view]);
        //print_r($response);exit;
        return $response;
    }

    protected function __moveUploadFile($obj_image, $title, $image_path, $is_public_path = true)
    {
        if ($obj_image->getClientOriginalExtension() == 'blob') {
            $name = str_slug($title) . '.jpg';
        } else {
            $name = str_slug($title) . '.' . $obj_image->extension();
        }
        $destinationPath = ($is_public_path)? public_path($image_path) : storage_path($image_path);

        if(!is_dir($destinationPath)){
            mkdir($destinationPath);
        }
        $imagePath = $destinationPath. $name;

        $obj_image->move($destinationPath, $name);
        return $name;
    }

    public function __encryptedPassword($password)
    {
        return md5(Config::get('constants.APP_SALT').$password);
    }

    protected function __generateUserHash($email)
    {
        return md5(Config::get('constants.APP_SALT').$email);
    }

    protected function __setSession($key, $value)
    {
        if($this->__call_mode == 'api')
            return false;

        $request = Request();
        $request->session()->put([
            $key => $value,
        ]);
        return true;
    }

    protected function __getSession($key)
    {
        if($this->__call_mode == 'api')
            return [];

        $request = Request();
        return $request->session()->get($key);
    }

    protected function __sendMail($identifier, $to, $params)
    {
        $template = MailTemplate::getByIdentifier($identifier);

        $mail_subject = $template->subject;
        $mail_body = $template->body;
        $mail_wildcards = explode(',', $template->wildcards);
        $to = trim($to);
        $from = trim($template->from);
        if(empty($from))
            $from = Setting::getByKey('send_email')->value;


        $mail_wildcard_values = [];
        foreach($mail_wildcards as $value) {
            $value = str_replace(['[',']'],'', $value);
            $mail_wildcard_values[] = $params[$value];
        }

        if(!in_array('[APP_URL]',$mail_wildcards)){
            $mail_wildcards[] = "[APP_URL]";
            $mail_wildcard_values[] = dynamicBaseUrl('');
        }

        $mail_body = str_replace($mail_wildcards, $mail_wildcard_values, $mail_body);
//        $headers = "From: $from" . "\r\n" ;
        //$headers .= "CC: $cc";

        try {
            // \Log::debug('Mail params: '.print_r(['to' => $to , 'from' => $from],1));
            Mail::send('emails.master', ['content' => $mail_body], function ($m) use ($to, $from, $mail_subject) {
                $m->from(config("mail.from.address"), config("mail.from.name"));
                $m->to($to)->subject($mail_subject);
            });
        }catch (\Exception $e){

            \Log::error($e->getMessage());
            return $this->__sendError("Mail Error",[$e->getMessage()],400);
        }

        return true;
    }

    protected function __base64ToJpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' );

        // split the string on commas
         //$data[ 0 ] == "data:image/png;base64"
         //$data[ 1 ] == <actual base64 string>
        //$data = explode( ',', $base64_string );
        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode($base64_string ) );

        // clean up the file resource
        fclose( $ifp );

        return $output_file;
    }

    // have to implement.
    protected function __sendMailNotInUse($to, $message, $data = [], $files = [], $ex_headers = []) {
        // init vars
        $charset = "utf-8";
        $headers = $ex_headers;
        $headers[] = "MIME-Version: 1.0";

        // - from
        $from = MAIL_USERNAME;
        if(isset($data['from'])) {
            if(is_array($data['from'])) {
                $headers[] = "From: ".$data['from'][1]." <".$data['from'][0].">";
                $from = $data['from'][0];
            } else {
                $headers[] = "From: <".$data['from'].">";
                $from = $data['from'];
            }
        }
        $from = MAIL_USERNAME;

        // cc
        if(isset($data['cc'])) {
            if(is_array($data['cc'])) {
                $headers[] = "Cc: ".$data['cc'][1]." <".$data['cc'][0].">";
            } else {
                $headers[] = "Cc: ".$data['cc'];
            }
        }

        // bcc
        if(isset($data['bcc'])) {
            if(is_array($data['bcc'])) {
                $headers[] = "Bcc: ".$data['bcc'][1]." <".$data['bcc'][0].">";
            } else {
                $headers[] = "Bcc: ".$data['bcc']."";
            }
        }
        // reply-to
        if(isset($data['reply-to'])) {
            if(is_array($data['reply-to'])) {
                $headers[] = "Reply-To: ".$data['reply-to'][1]." <".$data['reply-to'][0].">";
            } else {
                $headers[] = "Reply-To: <".$data['reply-to'].">";
            }
        }

        // to_email
        if(is_array($to)) {
            $to_email = $to[0];
        } else {
            $to_email = $to;
        }

        $headers[] = "Subject: {".$data["subject"]."}";
        $headers[] = "X-Mailer: PHP/".phpversion();

        if(count(((array) $files)) > 0) {
            $random_hash = md5(uniqid(time()));
            $headers[] = "Content-Type: multipart/mixed; boundary=PHP-mixed-".$random_hash;

            //define the body of the message.
            $htmlbody = $message;

            $message = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; boundary=PHP-alt-$random_hash\r\n\r\n";
            $message .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/html; 
          charset=\"".$charset."\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";


            //Insert the html message.
            $message .= $htmlbody;
            $message .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";

            for($f=0; $f<count($files); $f++) {
                $message .= $this->_prepareAttachment($files[$f],$random_hash);
            }
            $message .= "/r/n--PHP-mixed-$random_hash--";



        } else {
            $headers[] = "Content-type: text/html; charset=".$charset;
        }
        try {
            Mail::send('emails.default_template', ['content' => $message], function ($m) use ($to_email, $from, $data) {
                $m->from($from, APP_NAME);
                $m->to($to_email)->subject($data["subject"]);
            });
        }catch(\Exception $e){

        }


        //@mail($to_email, $data["subject"], $message, implode("\r\n", $headers));
        return;
    }


    public function isArray($string)
    {
        if(is_array($string)){
            return $string['value'];        
        }        
        return $string;
    }

    public function getLatLongFromAddress($address)
    {
        $address = urlencode($address);
        $google_api_key = 'AIzaSyB6D_44n_ZL_llSGghUIRDgWOx1ucPATtc';

        //print "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$address&key=AIzaSyB6D_44n_ZL_llSGghUIRDgWOx1ucPATtc";
        //exit;
        $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$address&key=$google_api_key");
        $json = json_decode($json);

        $response['lat'] = (isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}))? $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'} : '';
        $response['long'] = (isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}))? $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'} : '';
        $response['city'] = (isset($json->{'results'}[0]->{'address_components'}[2]->{'long_name'}))?$json->{'results'}[0]->{'address_components'}[2]->{'long_name'} : '';
        $response['zip_code'] = (isset($json->{'results'}[0]->{'address_components'}[6]->{'long_name'})) ? $json->{'results'}[0]->{'address_components'}[6]->{'long_name'} : '';
        $response['formatted_address'] = (isset($json->{'results'}[0]->{'formatted_address'})) ? $json->{'results'}[0]->{'formatted_address'} : '';

        return $response;
    }

    protected function __getFileContent($file_path, $is_header = 0){
        if ($xlsx = readXlsx::parse($file_path))
            $data = $xlsx->rows();
        if($is_header)
            return $data[0];

        if($data)
            return $data;
        return [];
    }

    protected function __setFlash($class,$message,$data = NULL){
        /**  $class: danger , info , success, */
        \Session::flash('class', $class);
        \Session::flash('message', $message);
        if(!empty($data)){
            \Session::flash('data', $data);
        }

    }

    protected function __delete($module, $id){

    }
}
