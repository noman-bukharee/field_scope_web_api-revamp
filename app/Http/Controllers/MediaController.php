<?php

namespace App\Http\Controllers;

use App\Exports\TagExport;
use App\Http\Requests\MediaStoreBulkRequest;
use App\Models\Project;
use App\Models\Category;
use App\Models\ProjectMedia;
use App\Models\ProjectMediaTag;
use App\Models\Sticker;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{

    function __construct()
    {
        parent::__construct();
//        $this->middleware(LoginAuth::class, ['only' => [
//                'store', 'index', 'show', 'edit', 'update', 'details'
//                 ]
//            ]
//        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function storeDetails(Request $request)
    {
        //<editor-fold desc="Validation">
        $param_rules['media'] = "array|required_without:deleted_media";
        $param_rules['media.*.project_id'] = [
            "required",
            "integer",
            Rule::exists('project', 'id')
                ->where('company_id', $request['company_id'])
                ->whereNull("deleted_at")
        ];

        $param_rules['media.*.category_id'] = [
            "required",
            "integer",
            Rule::exists('category', 'id')
                ->where('company_id', $request['company_id'])
                ->whereNull("deleted_at")
        ];
        $param_rules['media.*.note'] = "nullable";
        $param_rules['media.*.ref_id'] = "required|string";
        $param_rules['media.*.id'] = [
            "required",
            "integer",
            Rule::exists('project_media', 'id')
                ->whereNull("deleted_at")
        ];

        $param_rules['media.*.deleted_tags'] = "nullable|array";
        $param_rules['media.*.deleted_tags.*'] = "required|integer";

        $param_rules['media.*.tags'] = "nullable|array";
        $param_rules['media.*.tags.*.id'] = "required|integer";
        $param_rules['media.*.tags.*.quantity'] = "nullable|numeric";

        $param_rules['deleted_media'] = "array|required_without:media";
        $param_rules['deleted_media.*'] = "nullable|numeric";


        $response = $this->__validateRequestParams($request->all(), $param_rules);

        if ($this->__is_error == true)
            return $response;
        //</editor-fold>

        try {
            DB::beginTransaction();

            if (!empty($request['media'])) {
                $addedMedia = ProjectMedia::updateCategoryId_AndMediaTags($request['media']);
            }

            if (!empty($request['deleted_media'])) {
                ProjectMedia::whereIn('id', $request['deleted_media'])->delete();
            }

        } catch (\Illuminate\Database\QueryException $qe) {
            \Log::debug("QueryException: " . $qe->getMessage());
            DB::rollBack();
            return $this->__sendError("QueryException: " . $qe->getMessage(), [
                'file' => collect($qe->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $qe->getLine(),
            ],                        500);

        } catch (\Exception $e) {
            \Log::debug("Exception: " . $e->getMessage());
            DB::rollBack();
            return $this->__sendError("Exception: " . $e->getMessage(), [
                'file' => collect($e->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $e->getLine(),
            ],                        $e->getCode() ?: 400);
        } catch (\Throwable $t) {
            \Log::debug("Throwable: " . $t->getMessage());
            DB::rollBack();
            return $this->__sendError("Throwable: " . $t->getMessage(), [
                'file' => collect($t->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $t->getLine(),
            ],                        $t->getCode());
        }

        DB::commit();

        $this->__is_paginate = false;

        $message = 'Media Added Successfully';

        if (empty($addedMedia)) {
            $this->__collection = false;
            $message = "Request Successful";
        }
        return $this->__sendResponse('ProjectMedia', $addedMedia, 200, $message);
    }

    public function listView(Request $request){

        $this->__view = 'admin/photo_feed';

        $projects = Project::selectRaw('id,name')->where(['company_id' => $request['company_id']])->get();
        $user = User::selectRaw('id,first_name,last_name')->where(['company_id' => $request['company_id']])->whereNotNull('company_group_id')->get();
        $tag = Tag::selectRaw('id,name')->where(['company_id' => $request['company_id']])->get();

        $data['projects'] = $projects;
        $data['user'] = $user;
        $data['tag'] = $tag;
        $data['latest_photos'] = ProjectMedia::getLatestPhotos($request->all());
//        pd($data['latest_photos']->toArray(),'$data[\'latest_photos\']');

        $request->request->remove('company_id');
        $request->request->remove('call_mode');
        $request->request->remove('user_id');

        $this->__is_ajax = false;
        $this->__is_paginate = false;
        $this->__collection = false;
        $headers = [
            'Cache-Control' => 'no-cache, must-revalidate'
        ];
        return $this->__sendResponse('User', $data, 200, 'User list retrieved successfully.');
    }

    public function editPhoto(Request $request, $id){
        // $data['stickers'] = Sticker::all();
        $this->__view = 'admin/photo_feed_edit';
        $data['pMedia'] = ProjectMedia::getById($id,['tags_data','category']);
        //
        $projectid = ProjectMedia::where('id',$id)->pluck('project_id')->toArray();
        $data['project'] = Project::getById($projectid);
        
        $this->__is_paginate = false;
        $this->__collection = false;
        return $this->__sendResponse('User', $data  , 200, 'User list retrieved successfully.');
    }

    public function updatePhoto (Request $request,$id){
        $this->__is_paginate = false;
        $this->__is_ajax = true;
        $this->__collection = false;

        $pm = ProjectMedia::where(['id' => $id])->first();

        $pmUpdate = [];
        if ($request->hasFile('image')) {
//            if(file_exists(public_path(config('constants.MEDIA_IMAGE_PATH')).$pm['path'])){
//                unlink(public_path(config('constants.MEDIA_IMAGE_PATH')).$pm['path'] );
//            }
            $imageName = $request['user_id'] . "-" . time() . '_' . rand() . '.jpg';
            $request->file('image')->move(public_path(config('constants.MEDIA_IMAGE_PATH')), $pm['path']);
            //$pmUpdate['path'] = $imageName;
            \Log::debug('image'.print_r($request->all(),1));
        }

        //<editor-fold desc="Updating DB Block">
        $pmUpdate['note'] = $request->note;
        $pmRes = ProjectMedia::where(['id' => $id])->update($pmUpdate);
        $res = ProjectMediaTag::updateMediaTag($id,$request->all());
        //</editor-fold>

        //<editor-fold desc="Updating Watermark Block">
        $pMedia = ProjectMedia::where(['id' => $id])->first()->toArray();
        $pMediaTag = ProjectMediaTag::with(['ref_tags'])->where(['target_id' => $id , 'target_type' => 'media'])->get();
        $tags = [];
        foreach ($pMediaTag AS $key => $item){
            $tags[]['id'] = $item['ref_tags']['id'];
            $tags[]['company_id'] = $item['ref_tags']['company_id'];
            $tags[]['ref_id'] = $item['ref_tags']['ref_id'];
            $tags[]['ref_type'] = $item['ref_tags']['ref_type'];
            $tags[]['name'] = $item['ref_tags']['name'];
            $tags[]['has_qty'] = $item['ref_tags']['has_qty'];
        }

        $p = Project::getById($pMedia['project_id']);
        // $p = $p->toArray();

        $imgParam['latitude'] = $p['latitude'];
        $imgParam['longitude'] = $p['longitude'];
        $imgParam['inspection_date'] = $p['inspection_date'];
        $imgParam['user_id'] = $p['assigned_user_id'];
        $imgParam['category_id'] = $pMedia['category_id'];
        $imgParam['note'] = $pMedia['note'];
        $imgParam['image_path'] = $pMedia['path'];
        $imgParam['tags'] = ($pMediaTag->isNotEmpty()) ? array_column($pMediaTag->toArray(),'ref_tags') : [] ;
        $imgParam['mode'] = 'update';

        // public/uploads/media/1620146493626-1620147296-1523748270.jpg
        // ProjectMedia::addImageText_2($imgParam);
        //</editor-fold>

        if($res['error']){
            return $this->__sendError($res['error'],[$res['error']],'400');
        }

        // $project = Project::where(['id' => $pm['project_id'] ])->update(['is_updated' => 1]);
        // Update `updated_at` timestamp for the project
        Project::where('id', $pm['project_id'])->update(['updated_at' => now()]);

        return $this->__sendResponse('User', [], 200, 'Photo updated successfully.');
    }

    /** Get photo and its child entities details */
    public function details(Request $request,$id)
    {
        $this->__view = 'admin/photo_feed_details';
        $request['id'] = $id ;
        //<editor-fold desc="Validation">
        $param_rules['id'] = [
            'required',
            'int',
            Rule::exists('project_media', 'id')->whereNull('deleted_at')
        ];
        $response = $this->__validateRequestParams($request->all(), $param_rules);
        if ($this->__is_error)
            return $response;
        //</editor-fold>

        $projectid = ProjectMedia::where('id',$id)->pluck('project_id')->toArray();
        $media = ProjectMedia::getById($id,['tags_data','category']);
        $media['project'] = Project::getById($projectid);
        $media['area'] = Category::where('id', $media['category']['parent_id'])->first();
//        die('valdiation good: '.$id);

        $media['tags_list'] = collect($media['tags_data'])->implode('name', ', ');
        

        $this->__is_paginate = false;
        $this->__collection = false;
        return $this->__sendResponse('User', $media, 200, 'Photo Details retrieved successfully.');
    }
//Noman Bukharee
//     public function imageSync(Request $request)
//     {
//         //<editor-fold desc="Validation">
//         //        $param_rules['image_url'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:6144|dimensions:min_width=800,min_height=600'; // old
//         $param_rules['image_url'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:6144'; //new
//         $param_rules['ref_id'] = "required|alpha_num"; // 1563944418962
//         $param_rules['target_type'] = "required|in:query,category";
//         $param_rules['target_id'] = "required|int"; // 2
//         $param_rules['project_id'] = [
//             "required",
//             "int",
//             Rule::exists('project', 'id')
//                 ->where('company_id', $request['company_id'])
//                 ->whereNull('deleted_at')
//         ]; // 2
// //        $param_rules['tags']  = "required|json";
//         $param_rules['mode'] = "nullable|in:update";

//         $response = $this->__validateRequestParams($request->all(), $param_rules);
//         if ($this->__is_error == true)
//             return $response;
//         //</editor-fold>


//         $where = $request->only(['ref_id']);
//         $update = $request->only(['target_type', 'target_id', 'project_id']);


//         try {
//             $projectMedia = ProjectMedia::firstOrNew($where, $update);

//             if ($request->hasFile('image_url')) {
// //                \Log::debug("request->hasFile: ".print_r([
// //                     'target_type' => $request['target_type'],
// //                     'target_id' => $request['target_id'],
// //                     'project_id' => $request['project_id'],
// //                     'project_id' => $request->all(),
// //
// //                ],1));
//                 $imageTitle = "";
//                 if ($projectMedia->exists) {
//                     $imageTitle = pathinfo($projectMedia['path'], PATHINFO_FILENAME);
//                     $projectMedia->fill($update);
//                 } else {
//                     $imageTitle = $request['ref_id'] . "-" . time() . '_' . rand();
//                 }

//                 if ($request['target_type'] == 'query') {
//                     $projectMedia->path = $this->__moveUploadFile(
//                         $request->file('image_url'),
//                         $imageTitle,
//                         Config::get('constants.MEDIA_IMAGE_PATH')
//                     );
//                 } else if ($request['target_type'] !== 'query') {

//                     $now = now();
//                     $now->format('m/d/Y h:i A');
//                     $project = Project::getById($request['project_id']);

//                     $param = $request->all();
//                     $param['imageName'] = $imageTitle . "." . $request->file('image_url')->extension();

//                     $param['date'] = Project::getUserTimestamp($now)->format('m/d/Y h:i A');
//                     $param['lat'] = $project['latitude'];
//                     $param['long'] = $project['longitude'];

//                     //$param['tags'] = json_decode($request['tags'], TRUE);
//                     $imageTextResult = ProjectMedia::addImageText_3($param);

//                     if ($imageTextResult) {
//                         $projectMedia->path = $param['imageName'];
//                     }
//                 }
//             }

//             $projectMedia->save();
//         } catch (\Illuminate\Database\QueryException $qe) {
//             \Log::debug("QueryException: " . $qe->getMessage());
//             return $this->__sendError("QueryException: " . $qe->getMessage(), [
//                 'file' => collect($qe->getTrace())->filter(function ($value, $key) {
//                     return str_contains($value['file'], '/app/');
//                 })->values(),
//                 'line' => $qe->getLine(),
//             ],                        500);

//         } catch (\Exception $e) {
//             \Log::debug("Exception: " . $e->getMessage());
//             return $this->__sendError("Exception: " . $e->getMessage(), [
//                 'file' => collect($e->getTrace())->filter(function ($value, $key) {
//                     return str_contains($value['file'], '/app/');
//                 })->values(),
//                 'line' => $e->getLine(),
//             ],                        $e->getCode() ?: 400);
//         } catch (\Throwable $t) {
//             \Log::debug("Throwable: " . $t->getMessage());
//             return $this->__sendError("Throwable: " . $t->getMessage(), [
//                 'file' => collect($t->getTrace())->filter(function ($value, $key) {
//                     return str_contains($value['file'], '/app/');
//                 })->values(),
//                 'line' => $t->getLine(),
//             ],                        $t->getCode());
//         }

//         $this->__is_paginate = false;
//         $this->__collection = false;
//         return $this->__sendResponse('ProjectMedia', $projectMedia, 200, 'Media uploaded successfully');
//     }
public function imageSync(Request $request)
{
    // Define validation rules
    $param_rules['image_url'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:6144';
    $param_rules['ref_id'] = "required|alpha_num";
    $param_rules['target_type'] = "required|in:query,category";
    $param_rules['target_id'] = "required|int";
    $param_rules['project_id'] = [
        "required",
        "int",
        Rule::exists('project', 'id')
            ->where('company_id', $request['company_id'])
            ->whereNull('deleted_at')
    ];
    $param_rules['mode'] = "nullable|in:update";
    $param_rules['save_path'] = "nullable|string"; // Optional save path

    // Validate request
    $response = $this->__validateRequestParams($request->all(), $param_rules);
    if ($this->__is_error == true)
        return $response;

    $where = $request->only(['ref_id']);
    $update = $request->only(['target_type', 'target_id', 'project_id']);

    try {
        $projectMedia = ProjectMedia::firstOrNew($where, $update);

        if ($request->hasFile('image_url')) {
            $imageTitle = "";
            if ($projectMedia->exists) {
                $imageTitle = pathinfo($projectMedia['path'], PATHINFO_FILENAME);
                $projectMedia->fill($update);
            } else {
                $imageTitle = $request['ref_id'] . "-" . time() . '_' . rand();
            }

            $imageName = $imageTitle . '.' . $request->file('image_url')->extension();
            $imagePath = public_path(config('constants.MEDIA_IMAGE_PATH') . $imageName);

            // Move the uploaded file to the new location
            $request->file('image_url')->move(public_path(config('constants.MEDIA_IMAGE_PATH')), $imageName);

            $projectMedia->path = $imageName;
        }

        $projectMedia->save();
    } catch (\Exception $e) {
        \Log::debug("Exception: " . $e->getMessage());
        return $this->__sendError("Exception: " . $e->getMessage(), [
            'file' => collect($e->getTrace())->filter(function ($value, $key) {
                return str_contains($value['file'], '/app/');
            })->values(),
            'line' => $e->getLine(),
        ], $e->getCode() ?: 400);
    }

    return $this->__sendResponse('ProjectMedia', $projectMedia, 200, 'Media uploaded successfully');
}


}


