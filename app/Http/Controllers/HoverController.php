<?php

namespace App\Http\Controllers;

use App\Events\ProjectHoverJobUpdated;
use App\Http\Middleware\LoginAuth;
use App\Libraries\Hover;
use App\Models\Company;
use App\Models\CrmModel;
use App\Models\HoverField;
use App\Models\HoverJob;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class HoverController extends Controller
{
    private $_company,$hover;

    function __construct(Request $request)
    {
        parent::__construct();
        $this->middleware(LoginAuth::class, ['only' => [
            'index', 'getRedirectUri','setHoverDetails' ,'createJob' ,'getMeasurements' ,'getSampleMeasurements' ,'jobTestUpdate']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request['keyword'] = isset($request['keyword']) ? $request['keyword'] : NULL;

        $param = $request->all();
        $param['paginate'] = TRUE;
        $list = Project::getList($param);

        $this->__is_paginate = true;
        $this->__is_collection = true;
        return $this->__sendResponse('Project', $list, 200, 'Project list retrieved successfully.');
    }

    public function fieldList(Request $request, $id)
    {
        $hoverFields = HoverField::where(['hover_type_id' => $id])->get();

        $this->__is_ajax = true;
        return $this->__sendResponse('HoverField', $hoverFields, '200', __('app.success_listing_message'));
    }

    public function getRedirectUri(Request $request){

        $ref_code = Company::getUniqueHoverRefCode();

        $updateRes = Company::where(['id' => $request->company_id])->update(['hover_ref_code' => $ref_code]);

        $this->__is_redirect = true;
        $this->__view = 'admin/settings';
        $this->__is_ajax = false;
        $this->__is_paginate = false;
        $this->__collection = false;
        return $this->__sendResponse('User', [], 200, 'User list retrieved successfully.');
    }

    public function setAuthCode(Request $request,$code){

        Log::info("Hover Log: @setAuthCode: ".json_encode($request->all()));

        $this->__view = 'web/hover';

        $data = [];
        $company = Company::where(['hover_ref_code' => $code])->first();
        $company->hover_auth_code = $request['code'];
        $upRes = $company->save();

        \Log::debug("@setAuthCode: ".print_r([
                'company' => $company->toArray(),
                'upRes' => $upRes

                                               ],1));
        if($upRes){
            $data['message'] = "Your Hover authorization code is set.";
            CrmModel::where(['company_id' => $company['id']])->forceDelete();
        }else{
            $data['message'] = "Sorry! We're unable to set Hover authorization code.";
        }

        $this->__is_ajax = false;
        $this->__is_paginate = false;
        $this->__collection = false;
        return $this->__sendResponse('User', $data, 200, 'User list retrieved successfully.');
    }

    public function setHoverDetails(Request $request){

        $this->__view = 'admin/settings';
        $this->__is_redirect = true;

        $upRes = Company::where(['id' => $request['company_id']])->update(['hover_client_id' => $request['client_id'], 'hover_client_secret' => $request['client_secret'],]);

        CrmModel::where(['identifier' => 'hover', 'company_id' => $request['company_id']])->update([
            'access_token'=> "",
            'refresh_token'=> "",
            'token_type'=> "",
            'expires_at'=> "",
        ]);


        $this->__setFlash('danger', "Sorry! We're unable to set your hover details.");

        if($upRes){
            $this->__setFlash('success', "Hurray! Your hover is all set.");
        }

        $this->__is_ajax = false;
        $this->__is_paginate = false;
        $this->__collection = false;
        return $this->__sendResponse('User', [], 200, __(''));
    }

    public function createJob(Request $request)
    {

        $deliverablesIdsImploded = implode(',',HoverJob::DELIVERABLE_IDS);
        //<editor-fold desc="Validation">
        $param_rules['customer_name'] = "required";
        $param_rules['customer_email'] = "required";
        $param_rules['project_ref_id'] = "nullable";
        $param_rules['project_id'] = [
            "required",
            Rule::exists('project','id')
                ->where('company_id',$request['company_id'])
                ->whereNull('deleted_at')
        ];
        $param_rules['deliverable_id'] = "nullable|in:".$deliverablesIdsImploded;

        $response = $this->__validateRequestParams($request->all(),$param_rules);

        if($this->__is_error){
            return $response;
        }
        //</editor-fold>

        $this->__is_collection = false;
        $this->__collection = false;
        $this->__is_paginate = false;

        try {
            $company = Company::getById($request['company_id']);
            $currentUser = User::getById($request['user_id']);
            $hover = new Hover($company);
            $hoverJ = HoverJob::where(['project_id' => $request['project_id']])->first();

            //<editor-fold desc="User isn't on hover > get all hover users and update">
            if (empty($currentUser['hover_user_id'])) {

                $hUsers = $hover->listUsers();

                if (!empty($hUsers['results'])) {
                    $emails = array_pluck($hUsers['results'], 'email');
                    User::updateHoverUsers($hUsers, $request->all());

                    if (!in_array($currentUser['email'], $emails)) {
                        /** Checking if current user email is present in hover user email list*/
                        return $this->__sendError(
                            'Hover Error',
                            ['error' => "Your company hasn't added you on hover"],
                            400
                        );
                    }
                }
            }
            //</editor-fold>

            if (empty($company->hover_client_id) and empty($company->hover_client_secret)) {
                return $this->__sendError(
                    'Hover Error',
                    ['error' => "Your company hasn't been registered on hover"],
                    400
                );
            } else if (!empty($hoverJ)) {
                return $this->__sendResponse(
                    'Project',
                    ['job_id' => $hoverJ['job_id']],
                    200,
                    'Job already created for project.'
                );
            } else {

                $project = Project::find($request['project_id']);

                $addrParts = Project::getAddressParts($project->address1);

                $request['name'] = $project['name'];
                $request['location_line_1'] = $addrParts[0];
                $request['location_city'] = $addrParts[1];
                $request['location_region'] = $addrParts[2];
                $request['location_postal_code'] = $addrParts[3];
                $request['location_country'] = "USA";
                $request['current_user_email'] = $currentUser['email'];
                $response = $hover->createJob($request->all());

                if (empty($response['job']['id'])) {
                    return $this->__sendError('Hover Error', ['error' => "Unable to create a job on hover."], 400);
                }
                $hover->updateTestJob($response['job']['id'], 'complete', $currentUser['email']);
                $res = HoverJob::firstOrCreate(
                    ['project_id' => $request['project_id']],
                    ['job_id' => $response['job']['id'],
                        'deliverable_id' => $response['job']['deliverable_id'],
                        'state' => $response['job']['state'],
                        'company_id' => $request['company_id']]
                );

                if (empty($company->hover_webhook_id)) {
                    $hover->createWebhook();
                } else if (empty($company->hover_webhook_verified_at)) {
                    $hover->retryWebhookVerification();
                }
            }
        }
        catch (\Illuminate\Database\QueryException $qe) {
                \Log::debug("QueryException: " . $qe->getMessage());
                return $this->__sendError("QueryException: " . $qe->getMessage(), [
                    'file' => collect($qe->getTrace())->filter(function ($value, $key) {
                        return str_contains($value['file'], '/app/');
                    })->values(),
                    'line' => $qe->getLine(),
                ],                        500);

            } catch (\Exception $e) {
                \Log::debug("Exception: " . $e->getMessage());
                return $this->__sendError("Exception: " . $e->getMessage(), [
                    'file' => collect($e->getTrace())->filter(function ($value, $key) {
                        return str_contains($value['file'], '/app/');
                    })->values(),
                    'line' => $e->getLine(),
                ],                        $e->getCode() ?: 400);
            } catch (\Throwable $t) {
                \Log::debug("Throwable: " . $t->getMessage());
                return $this->__sendError("Throwable: " . $t->getMessage(), [
                    'file' => collect($t->getTrace())->filter(function ($value, $key) {
                        return str_contains($value['file'], '/app/');
                    })->values(),
                    'line' => $t->getLine(),
                ],                        $t->getCode());
            }


        \Log::debug("@createJob End: ");
        $this->__is_collection = false;
        $this->__collection = false;
        $this->__is_paginate = false;
        return $this->__sendResponse('Project', ['job_id' => $response['job']['id']], 200, __('app.success_store_message'));
    }


    public function jobTestUpdate(Request $request){

        //<editor-fold desc="Validation">
        $param_rules['job_id'] = "required";
        $param_rules['state'] = "required|in:complete,completed,uploading,processing,failed";

        $response = $this->__validateRequestParams($request->all(),$param_rules);

        if($this->__is_error){
            return $response;
        }
        //</editor-fold>


        $company = Company::getById($request['company_id']);
        $currentUser    = User::getById($request['user_id']);
        $hover = new Hover($company);

        $hover->updateTestJob($request['job_id'],$request['state'],$currentUser['email']);
        $response = $hover->getCompleteResponse();

        if($response['code'] != 200){
            return $this->__sendError('Hover Error',$response['message'] ?:"No Message",$response['code'] );
        }

        $this->__is_collection = false;
        $this->__collection = false;
        $this->__is_paginate = false;
        return $this->__sendResponse('Project', $response, 200, __('app.success_store_message'));
    }

    public function getMeasurements(Request  $request){

        //<editor-fold desc="Validation">
        $param_rules['job_id'] = "required|exists:hover_jobs,job_id,deleted_at,NULL";
        $param_rules['project_ref_id'] = "nullable|exists:hover_jobs,project_ref_id,deleted_at,NULL";
        $param_rules['version'] = "required|in:full_json";

        $response = $this->__validateRequestParams($request->all(),$param_rules);

        if($this->__is_error){
            return $response;
        }
        //</editor-fold>

        try {
        $hJobM = new HoverJob();
        $hJob = $hJobM::getBy(['job_id' => $request->job_id ]);

        $request['type'] = 2;

        $tags = Tag::where(['company_id' => $request['company_id'] ])->select('id','hover_field_id','has_qty','ref_id','ref_type')->get();
        $projectHoverFields = Project::find($hJob[0]->project_id)->hover_fields->keyBy('id');



        $this->hover = new Hover(Company::getById($request['company_id']));

        if (empty($hJob[0]->json_response) or empty($hJob[0]->file_path)) {

            /** Get Meausurements from Hover API*/
            $res = $this->getMeasurementsFromHoverAndUpdateDB($hJob[0],$request->version);

            if (!$res) {
                return $this->__sendError('Notice', ['error' => 'Hover report hasn\'t been saved to db.'], 400);
            }
            $hoverFields = $this->hover->parseJobCompletely(); // no param cuz hover lib already has it.

            ProjectMedia::updateTagsQtyWithHover($hoverFields,$hJob[0]->project_id); // updating the project's tags

        } else {
            /** Get Meausurements from DB JSON*/
            $hoverFields = $this->hover->parseJobCompletely($hJob[0]->json_response);
        }
            $fieldTitles = $hoverFields->pluck('hover_field_type_title', 'hover_field_type')->unique();
            $hoverFields = $hoverFields->map(function ($el) use ($tags,$projectHoverFields) {

                /** Updating previously added value */
                if (!empty($projectHoverFields[$el->id]->pivot->value)) {
                    $el['hover_value'] = number_format($projectHoverFields[$el->id]->pivot->value,2);
                }

                /** Adding tags info */
                $el['tags'] = $tags->where('hover_field_id', $el->id)->values();
                return $el;

            })->groupBy('hover_field_type')->map(function ($el, $key) use ($fieldTitles) {
                /** Grouping by type */
                return ['title' => $fieldTitles[$key], 'fields' => $el->toArray()];
            })->values();


        } catch (\Illuminate\Database\QueryException $qe) {
            \Log::debug("QueryException: " . $qe->getMessage());
            return $this->__sendError("QueryException: " . $qe->getMessage(), [
                'file' => collect($qe->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $qe->getLine(),
            ],                        500);

        } catch (\Exception $e) {
            \Log::debug("Exception: " . $e->getMessage());
            return $this->__sendError("Exception: " . $e->getMessage(), [
                'file' => collect($e->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $e->getLine(),
            ],                        $e->getCode() ?: 400);
        } catch (\Throwable $t) {
            \Log::debug("Throwable: " . $t->getMessage());
            return $this->__sendError("Throwable: " . $t->getMessage(), [
                'file' => collect($t->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $t->getLine(),
            ],                        $t->getCode());
        }


//        $this->__collection = false;
//        $this->__is_paginate = false;
        return $this->__sendResponse('HoverField',$hoverFields->toArray(), 200 ,__('app.success_listing_message'));
    }

    public function getMeasurementsReport(Request  $request){

        //<editor-fold desc="Validation">
        $param_rules['job_id'] = "required|exists:hover_jobs,job_id,deleted_at,NULL";
        $param_rules['project_ref_id'] = "nullable|exists:hover_jobs,project_ref_id,deleted_at,NULL";
        $param_rules['version'] = "nullable|in:full_json";

        $response = $this->__validateRequestParams($request->all(),$param_rules);

        if($this->__is_error){
            return $response;
        }
        //</editor-fold>

//        $hJobM = new HoverJob();
//        $hJob = $hJobM::getBy(['job_id' => $request->job_id ]);
//
//        if(empty($hJob[0]->file_path)){
//            return $this->__sendError('Report Not Found',[],400);
//        }
//
//        /** Will be needed if client wanted to fetch report without depending on fetching json */
//       if(FALSE){
//           $request['type'] = 2;
//
//           $tags = Tag::getCompanyHoverFields($request->all());
//
//           $hover = new Hover(Company::getById($request['company_id']));
//
//           if(empty($hJob[0]->json_response) OR empty($hJob[0]->file_path)){
//               /** Get Meausurements from Hover API*/
//               $measurements = $hover->getFullMeasurements($hJob[0]->job_id,'json',$request->version);
//
//               $filePath = $hover->getFullMeasurements($hJob[0]->job_id,'pdf');
//
//
//               if($hover->getCompleteResponse()['code'] != 200){
//                   return $this->__sendError('Notice',['error' => 'Hover report hasn\'t been completed yet.'],400);
//               }
//
//               /** updating to db*/
//               $res = $hJobM->updateResponse($request->all(),$measurements,$filePath);
//               if (!$res) {
//                   return $this->__sendError('Notice', ['error' => 'Hover report hasn\'t been saved to db.'], 400);
//               }
//               //$photoViewResponse = $hover->parseJob($tags);
//               $photoViewResponse = $hover->parseJobCompletely($hJob[0]->json_response,$tags);
//           } else {
//               /** Get Meausurements from DB JSON*/
//               //$photoViewResponse = $hover->parseJob($tags,$hJob[0]->json_response);
//               $photoViewResponse = $hover->parseJobCompletely($hJob[0]->json_response,$tags);
//           }
//       }

//        $this->__collection = false;
//        $this->__is_paginate = false;
//        return $this->__sendResponse('Tag',['link' => url(config('constants.HOVER_FILE_PATH').$hJob[0]->file_path)], 200 ,__('app.success_listing message'));
    }

    public function getSampleMeasurements(Request  $request){
//        //<editor-fold desc="Validation">
//        $param_rules['job_id'] = "nullable";
////        $param_rules['project_id'] = "required|exists:hover_jobs,project_id,deleted_at,NULL";
//        $param_rules['version'] = "required|in:full_json";
//
//        $response = $this->__validateRequestParams($request->all(),$param_rules);
//
//        if($this->__is_error){
//            return $response;
//        }
//        //</editor-fold>
//
//        $request['type'] = 2;
//        $tags = Tag::getCompanyHoverFields($request->all());
//
//        $hover = new Hover(Company::getById($request['company_id']));
//        $tagResponse = $hover->parseJob($tags,$hover->sampleJob);
//
//        $this->__collection = true;
//        $this->__is_paginate = false;
//        return $this->__sendResponse('Tag',$tagResponse, 200 ,__('app.success_listing message'));
    }

    public function getMeasurementsFromHoverAndUpdateDB($hJob,$reportVersion='full_json'){

        if(!($this->hover instanceof Hover)){
            throw new \Exception("Property is not the hover service.");
        }

        /** Get Meausurements from Hover API*/
        $measurements = $this->hover->getFullMeasurements($hJob->job_id,'json',$reportVersion);
        $filePath = $this->hover->getFullMeasurements($hJob->job_id,'pdf');

        if ($this->hover->getCompleteResponse()['code'] != 200) {
            $this->__collection = false;
            $this->__is_paginate = false;
            return $this->__sendResponse('Tag',[], 202 ,'Hover report hasn\'t been completed yet.');
        }

        $jobId = $hJob->job_id;

        \Log::debug("@getMeasurementsFromHoverAndUpdateDB: ".print_r([
                                                                         'jobId' => $jobId,
                                                                         'measurements' => $measurements,
                                                                         'filePath' => $filePath
                                                                     ],1));
        $hJobM = new HoverJob();
        /** updating to db*/
        $res = $hJobM->updateResponse($jobId,$measurements,$filePath);

        return $res;
    }

    /** Hover Event Capture endpoint */
    public function webhook(Request $request,$token){

        //<editor-fold desc="Validation">
        $request['hover_ref_code'] = $token;
        $param_rules['hover_ref_code'] = [
            'required',
            Rule::exists('company','hover_ref_code')
        ];

        $response = $this->__validateRequestParams($request->all(),$param_rules);

        if($this->__is_error){
            return $response;
        }
        //</editor-fold>

        try{
            $company = Company::where(['hover_ref_code' => $token])->first();

            \Log::debug("Hover Event Captured: {$request->event}"
                        . print_r(
                            [
                                'job_id' => $request->job_id,
                                'state' => $request->state,
                            ],
                            1
                        )
            );


            $this->hover = new Hover($company);
            if($request->event == 'webhook-verification-code' && empty($company->hover_webhook_verified_at)){
                $this->hover->verifyWebhook($request['code']);
            }else if($request->event == 'job-state-changed') {
                $hoverJob = HoverJob::where(['job_id' => $request->job_id])->first();
                $hoverJob->state = $request->state;
                $hoverJob->updated_by_hover_at = now();
                $hoverJob->save();

                if($request->state == 'complete'){
                     event(new ProjectHoverJobUpdated($hoverJob));

                    /** Get Meausurements from Hover API*/
                    $res = $this->getMeasurementsFromHoverAndUpdateDB($hoverJob);
                }

                \Log::debug("job_id: {$request->job_id}");
            }

        } catch (\Illuminate\Database\QueryException $qe) {
            \Log::debug("QueryException: " . $qe->getMessage());
            return $this->__sendError("QueryException: " . $qe->getMessage(), [
                'file' => collect($qe->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $qe->getLine(),
            ],                        500);

        } catch (\Exception $e) {
            \Log::debug("Exception: " . $e->getMessage());
            return $this->__sendError("Exception: " . $e->getMessage(), [
                'file' => collect($e->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $e->getLine(),
            ],                        $e->getCode() ?: 400);
        } catch (\Throwable $t) {
            \Log::debug("Throwable: " . $t->getMessage());
            return $this->__sendError("Throwable: " . $t->getMessage(), [
                'file' => collect($t->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $t->getLine(),
            ],                        $t->getCode());
        }

        $this->__collection = false;
        $this->__is_paginate = false;
        return $this->__sendResponse('Tag',[], 200 ,__('app.success_store_message'));
    }

    /** Testing via postman method */
    public function createWebhook(Request $request){

        $company = Company::getById($request['company_id']);
//        $currentUser    = User::getById($request['user_id']);
        $hover = new Hover($company);

//        $hover->createWebhook();
//        $hover->fetchWebhooks();
//        $hover->retryWebhookVerification(3666);
//        $hover->deleteWebhook(3667);


        return response()->json();

    }

    public function testJob(Request $request){

        Log::info("testJob Log: ".json_encode($request->all()));
        echo json_encode($request->all());
    }

    public function parseJob(Request $request){
        $hover = new Hover(Company::getById($request['company_id']));
        $hover->parseReport();
    }

    public function measurementUpdate(Request $request){

        //<editor-fold desc="Validation">
        $param_rules['project_id'] = [
            "required",
            Rule::exists('project','id')
                ->where('company_id',$request['company_id'])
                ->whereNull('deleted_at')
        ];
        $param_rules['overwrite_all'] = "nullable|boolean";
        $param_rules['fields.*.id'] = "required|int";
        $param_rules['fields.*.value'] = "nullable|string";

        $response = $this->__validateRequestParams($request->all(),$param_rules);

        if($this->__is_error){
            return $response;
        }
        //</editor-fold>

        $project = Project::find($request['project_id']);

        $fields = collect($request['fields'])->keyBy('id')->map(function ($item,$key){
            $item['value'] = empty($item['value']) ? 0 : $item['value'];
            return collect($item)->only(['value']);
        });

        $res = $project->hover_fields()->sync($fields);

        $overwrite = $request['overwrite_all'] ?: false;
        $tagResult = false;
        $tagResult = ProjectMedia::updateTagsQtyWithHover($request['fields'],$request['project_id'],$overwrite);

        if (collect($res)->flatten()->count() > 0) {
            $message = __('app.success_update_message');

        }else{
            $message = $tagResult ? "Tags Updated" : "No Changes To Update";
        }

        return $this->__sendResponse('HoverField',[], 200 ,$message);
    }


}

