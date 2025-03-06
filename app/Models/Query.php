<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use App\Models\QueryTag;

use App\Libraries\Helper;

class Query extends Model
{
    protected $table = "query";
    protected $fillable = ['company_id', 'query', 'type', 'category_id', 'options', 'photo_view_id','is_required', 'custom_tag', 'created_at' ,'order_by', 'image_url'];
    //Add Multiple Help Photos
    protected $casts = [
        'image_url' => 'array', // Automatically casts JSON to array
    ];
    use SoftDeletes;

    public static function getList($param)
    {
        $query = self::select();
        $query->where('company_id', $param['company_id']);

        if (!empty($param['keyword'])) {
            //echo "AAA";die;
            $keyword = $param['keyword'];
//            $query->whereRaw("(`category`.`name` LIKE '%$keyword%' OR `min_quantity` LIKE '%$keyword%')");
            $query->where(function ($where) use ($keyword) {
                $where->orWhere('query', 'LIKE', "%$keyword%");
            });
        }
//        print_r($query->get());

        return $query->paginate(Config::get('constants.PAGINATION_PAGE_SIZE'));
    }

    public static function queryDatatable($param = []){
        $output = [];
        parse_str($param['custom_search'], $output);

        $sort = [
            'query'
        ];

        $query = self::join('category AS c','c.id','=','query.category_id')->selectRaw('query.*,c.name AS category_name');

        $query->where('query.company_id', $param['company_id']);

        if (!empty($output['keyword'])) {
            $keyword = $output['keyword'];
            $query->where(function ($where) use ($keyword) {
                $where->orWhere('query', 'LIKE', "%$keyword%");
                $where->orWhere('c.name', 'LIKE', "%$keyword%");
            });
        }

        $data['total_record'] = count($query->get());
        // $query = $query->take($param['length'])->skip($param['start'])->orderBy('order_by','ASC');
        //Noman
        $start = isset($params['start']) ? max(0, (int)$params['start']) : 0;
        $length = isset($params['length']) ? max(1, (int)$params['length']) : 100;
        $query = $query->take($params['length'])->orderBy('order_by','ASC')->skip($start)->take($length);


        $query = $query->get();
//        Helper::pd($query->get()->toArray(),'$query');
        $data['records'] = $query;
        return $data;
    }

    public static function getByCategoryId($id,$param = []){
        /*working late*/
        $query = self::select();
        $data = $query->where('id', $id)->first();

        $query = self::select();

        $query->where('category_id', $data['category_id']);

        if (!empty($param['orderBy'])) {
            $query->orderBy($param['orderBy'][0], $param['orderBy'][1]);
        }

        $category = $query->get();
        return $category;
    }

    public static function getById($id){

        $query = self::select();
        return $query->where('id', $id)
            ->first();
    }

    public static function getAllBy($id){

        $query = self::select();
        return $query->where('id', $id)
            ->first();
    }

    public static function getWithUserResponse($param)
    {
        $query = self::with(['userResponse' => function ($query) use ($param) {
            $query->where('query_id', $param['id']);
        }])->select();

        if (!empty($param['id'])) {
            $query->where('id', $param['id']);
        }

        return $query->get();
    }

//     public static function updateQuery($request){
//         $question = $request['question'];
//         $queryId = $request['query_id'];
//         $type = $request['type'];
//         //$orderBy = $request['order_by'];
//         $options =   $request['options'];
//         $photo_view =   $request['photo_view'];
//         $area =   $request['area'];
//         $image_set =   $request['image_set'];


//                 $queryUpdateData = [];

//                 if ($request->file('help_photo')) {
//                     $queryUpdateData['image_url'] =
//                         Helper::uploadFile($request->file('help_photo'), 'sample_query', Config::get('constants.MEDIA_IMAGE_PATH'));
//                 }else if ($image_set == 'false') {
//                     \Log::debug('image else '.$image_set);
//                     $queryUpdateData['image_url'] = "";
//                 }

//                 $queryUpdateData['company_id'] = $request['company_id'];
//                 $queryUpdateData['is_required'] = filter_var($request['is_required'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
//                 $queryUpdateData['query'] = $question;
//                 $queryUpdateData['type'] = $type;
//                 $queryUpdateData['updated_at'] = date('Y-m-d H:i:s');
//                 $queryUpdateData['category_id'] = !empty($area) ? $area : NULL;
//                 $queryUpdateData['photo_view_id'] = !empty($photo_view) ? $photo_view : NULL;


//                 if (!empty($options) && ($type == 'text' || $type == 'date' || $type == 'sign')) {
//                     $queryUpdateData['options'] = '';
//                 } else {

// //                    if (!empty($naRule[$key])) {
// //                        /** IF NA is selected */
// //
// //
// //                    } else {
// //
// //                        /** IF NA is not selected
// //                         *      -> Need to remove NA if exists in options
// //                         */
// //                        $queryUpdateData['photo_view_id'] = NULL;
// //
// //                        if (in_array('N/A', $option[$key])) {
// //                            /** N.A is IN request options
// //                             *      -> Must remove it (cuz NA wasn't selected) *
// //                             */
// //                            if (($optionKey = array_search('N/A', $option[$key])) !== false) {
// //                                unset($option[$key][$optionKey]);
// //                            }
// //                        }
// //                    }
//                     $queryUpdateData['options'] = trim(implode(',', $options), ' ,');

//                     if(empty($queryUpdateData['options'])){
//                         return ['error' => 'Required Options Missing at question.'];
//                     }
//                 }
//                 \Log::debug('is_required data: '.print_r([!empty($request['is_required'])] , 1));
//                 \Log::debug('request data: '.print_r($request->all() , 1));
//                 \Log::debug('update data: '.print_r($queryUpdateData , 1));

//                 $qResult = self::updateOrCreate(['id' => $queryId],$queryUpdateData);


//         return $qResult;
//     }
    public static function updateQuery($request)
    {
        $queryId = $request['query_id'];
        $type = $request['type'];
        $options = $request['options'];
        $photo_view = $request['photo_view'];
        $area = $request['area'];
        $image_set = $request['image_set'];

        // Fetch existing query if updating
        $query = $queryId ? self::find($queryId) : null;
        $existingImages = $query && $query->image_url ? $query->image_url : [];

        $queryUpdateData = [
            'company_id' => $request['company_id'],
            'is_required' => filter_var($request['is_required'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'query' => $request['question'],
            'type' => $type,
            'updated_at' => now(),
            'category_id' => !empty($area) ? $area : null,
            'photo_view_id' => !empty($photo_view) ? $photo_view : null,
        ];

        // Handle image updates
        if ($image_set === 'false') {
            $queryUpdateData['image_url'] = []; // Clear all images
        } else {
            // Get retained existing images from the form (defaults to empty array if none sent)
            $retainedImages = $request->input('existing_images', []);

            // Handle new image uploads
            $newImageUrls = [];
            if ($request->hasFile('help_photo')) {
                foreach ($request->file('help_photo') as $file) {
                    $path = Helper::uploadFile($file, 'sample_query', Config::get('constants.MEDIA_IMAGE_PATH'));
                    if ($path) {
                        $newImageUrls[] = $path;
                    }
                }
            }

            // Combine retained existing images with new uploads
            $queryUpdateData['image_url'] = array_merge($retainedImages, $newImageUrls);
        }

        if (!empty($options) && in_array($type, ['text', 'date', 'sign'])) {
            $queryUpdateData['options'] = '';
        } elseif (!empty($options)) {
            $queryUpdateData['options'] = trim(implode(',', $options), ' ,');
            if (empty($queryUpdateData['options'])) {
                return ['error' => 'Required Options Missing at question.'];
            }
        }

        \Log::debug('update data: ' . print_r($queryUpdateData, 1));

        return self::updateOrCreate(['id' => $queryId], $queryUpdateData);
    }

    public static function deleteQuery($id){
        return self::where('id',$id)->delete();
    }

    public static function parseSurvey($survey)
    {
//        die("$survey");
        $parsedArr = [];
        foreach ($survey AS $key => $item) {

            if(!empty($item['image_url'])){
                $item['image_url'] = env('BASE_URL').config('constants.MEDIA_IMAGE_PATH').$item['image_url'];
            }
            if($item['type'] == 'text' || $item['type'] == 'date' || $item['type'] == 'sign' ){
                $item['options'] = [];
            }
            else{
                /*Checkbox , Radio*/
//                $item['options'] = 'N/A,' . $item['options'];
                $opExp = explode(',', $item['options']);
                $options_data = [];
                foreach ($opExp AS $opKey => $opItem) {
                    $options_data[] = [
                        'title' => $opItem,
                        'is_selected' => false
                    ];
                }
                $item['options'] = $options_data;

                if (in_array('N/A',$opExp)) {
                    $item['has_na'] = TRUE;
                }else{
                    $item['has_na'] = FALSE;
                }
            }
            $parsedArr[$key] = $item;
        }
        return $parsedArr;
    }

    public static function reOrder($reOrderParam, $companyId, $start = 0){
//        echo '<pre>'; print_r($reOrderParam); exit;
        foreach ($reOrderParam AS $key => $item){
            $res = self::where('id',$item['id'] )->update(['order_by' => ($start ) + ($item['new_position'])]);
            /*echo "update `category` set `order_by` = ".((($start ) + ($item['new_position']+1)) ).",
            `updated_at` = 2019-08-29 11:26:37 where `id` = ".$item['id']." and `category`.`deleted_at` is null)\n";*/
            if(empty($res)){
                /*Failed*/
                return ['error' => 'Error in updating at '.$key ];
            }
        }
        return true;
    }

    //<editor-fold desc="Accessors">
    public function getOptionsAttribute($value)
    {
        if (in_array($this->type, ['checkbox', 'radio'])) {
            return collect(explode(',', $value))->map(function ($el) {
                return [
                    'title' => $el,
                    'is_selected' => false
                ];
            });
        } else {
            return $value;
        }
    }
    //</editor-fold>

    /*Relations Starts*/

    public function userResponse(){
        return self::hasMany('App\Models\ProjectQuery', 'query_id');
    }

    public function tags(){
        return $this->hasMany('App\Models\Tag', 'ref_id', 'id')->where('ref_type','=', 'query');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }
}
