<?php

namespace App\Models;

use App\Libraries\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Config;

class ProjectQuery extends Pivot
{
    protected $table = "project_query";

    public $survey = [];

    protected $fillable = [
        'project_id', 'query_id', 'query', 'response', 'created_at', 'updated_at', 'signature', 'date'
    ];

    public static function getById($id){

        $query = self::select();
        return $query->where('id', $id)
            ->first();
    }

    public static function getByProjectId_bk($id){

        $query = self::select();
        return $query->where('project_id', $id)
            ->get();
    }

    public static function createOrUpdateSurvey($survey, $projectId = null , $submittedAt ="", $signature_url="")
    {
        $pSurvey = [];
        $updatedSurvey = collect([]);

        foreach ($survey AS $key => $item) {
            $response = '';
            if($item['type'] == 'text' || $item['type'] == 'date' || $item['type'] == 'sign'){
                $response = $item['user_response'];
            }
            else{
                /** radio & checkbox */
                $selectedOp = [];
                foreach ($item['options'] AS $opKey => $opItem) {
                    if ($opItem['is_selected']) {
                        $selectedOp[] = $opItem['title'];
                    }
                }
                if(empty($selectedOp)){
                    $selectedOp[] = 'N/A';
                }
                $response = implode(',',$selectedOp);
            }

            $pSurvey['response'] = $response;

            $where = [
                'project_id' => $projectId?: $item['project_id'],
                'query_id' => $item['query_id']
            ];

            $updatedSurvey[$key] = $where;
            self::updateOrCreate($where,$pSurvey);
        }

        return self::with(['survey'])->whereIn('project_id',$updatedSurvey->pluck('project_id'))
            ->whereIn('query_id',$updatedSurvey->pluck('query_id'))->get();
    }

    public static function updateSurvey($survey,$projectId,$submittedAt="",$signature_url="")
    {
//        Helper::pd($survey,'$survey');
        $pSurvey = [];
        foreach ($survey AS $key => $item) {
            $response = '';
            if($item['type'] == 'text' || $item['type'] == 'date'){
                $response = $item['user_response'];
            }
            else if($item['type'] == 'sign'){
                $image = $item['user_response'];  // your base64 encoded
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'q-'.$item['id'] . "-" . time() . '_' . rand().'.'.'png';
                $response = $imageName;
                \File::put(Config::get('constants.MEDIA_IMAGE_PATH'). '/' . $imageName, base64_decode($image));
            }
            else{
                $selectedOp = [];
                foreach ($item['options'] AS $opKey => $opItem) {
                    if ($opItem['is_selected']) {
                        $selectedOp[] = $opItem['title'];
                    }
                }
                $response = implode(',',$selectedOp);
//                if (empty($response)) {
//                    $res['error_data'] = $item;
//                    $res['error'] = 'Response field is empty';
//                    return $res;
//                }
            }

            /*$pSurvey[$key]['date'] = $submittedAt;
            $pSurvey[$key]['signature'] = $signature_url;*/

            $pSurvey['query'] = $item['query'];
            $pSurvey['response'] = $response;
            $pSurvey['created_at'] = date('Y-m-d H:i:s');

            $updateRes = self::updateOrCreate(['project_id' => $projectId , 'query_id' => $item['id'] ],$pSurvey);
            if (empty($updateRes)) {
                $res['error_data'] = [$pSurvey,['project_id' => $projectId , 'query_id' => $item['id'] ]];
                $res['error'] = 'Failed to update query id: '.$item['id'];
                return $res;
            }
        }
        return self::getByProjectId($projectId);

    }

    public static function getByProjectId($projectId){
        $queries = self::
            join('query AS q','q.id' , '=' ,'project_query.query_id')
            ->selectRaw('
            q.id,  
            q.company_id,  
            q.query,                        
            q.type,      
            q.category_id,  
            q.options,
            project_query.response AS response')->where('project_id',$projectId)
            ->get()->toArray();
        return self::parseSurvey($queries);
    }

    public static function parseSurvey($survey)
    {
        $parsedArr = [];
        foreach ($survey AS $key => $item) {
            $imagePath = url(config("constants.MEDIA_IMAGE_PATH")) . "/";
            if(!empty($item['image_url'])){
                $item['image_url'] = $imagePath.$item['image_url'];
            }

            if ($item['type'] == 'text' || $item['type'] == 'date' ) {
                $item['options'] = [];
                $item['user_response'] = $item['response'];
            } else if ($item['type'] == 'sign') {
                $item['user_response'] = $imagePath.$item['response'];
            } else {
                /** Checkbox , Radio*/
//                $item['options'] = 'N/A,' . $item['options'];

                $options_data = [];

                if(is_array($item['options'])){
                    /** Fill Responded survey*/
                    foreach ($item['options'] AS $opKey => $opItem) {
                        $responseExploded = explode(',', $item['response']);

                        $item['options'][$opKey]['is_selected'] = in_array($opItem['title'], $responseExploded) ? true : false;
                    }
                    $item['user_response'] = "";

                }else{
                    /** Fill non-responded survey*/
                    $opExploded = explode(',', $item['options']);
                    foreach ($opExploded AS $opKey => $opItem) {
                        $responseExploded = explode(',', $item['response']);
                        $options_data[] = [
                            'title' => $opItem,
                            'is_selected' => in_array($opItem, $responseExploded) ? true : false
                        ];
                    }
                    if (in_array('N/A',$opExploded)) {
                        $item['has_na'] = TRUE;
                    }else{
                        $item['has_na'] = FALSE;
                    }

                    $item['user_response'] = "";
                    $item['options'] = $options_data;
                }
            }
            $parsedArr[$key] = $item;
        }

        return $parsedArr;
    }

    //<editor-fold desc="Accessors">
    public function getOptionsAttribute($value)
    {
        /** For this accessor to work properly, this model (ProjectQuery) needs to have parent model (Query) data
         *  via join like it has app/Models/Project.php @surveyResponse
         */

        if (in_array($this->type, ['checkbox', 'radio'])) {
            $response = $this->response;
            return collect(explode(',', $value))->map(function ($el) use($response){
                return [
                    'title' => $el,
                    'is_selected' => ($response === $el) ? true : false
                ];
            });
        } else {
            return $value;
        }
    }

    public function getResponseAttribute($value)
    {
        /** For this accessor to work properly, this model (ProjectQuery) needs to have parent model (Query) data
         *  via join like it has app/Models/Project.php @surveyResponse
         */
        // getting data from relation $this->relations['survey']['type']

        if(in_array($this->type,['checkbox','radio'])){
            $responseArr = explode(',',$value);
            return collect($this->options)->map(function ($el) use($responseArr){
                $el['is_selected'] = in_array($el['title'],$responseArr);
                return $el ;
            });
        }else if(in_array($this->type,['sign'])){
            return $this->mediaResponse;
        }
        else {
            return $value;
        }
    }
    //</editor-fold>

    //<editor-fold desc="Relationship">
    public function survey(){
        /** IF we use this relationship and not via join this model's accessors won't work */
        return $this->belongsTo('App\Models\Query','query_id');
    }
//
//    public function response()
//    {
//        return $this->morphMany('App\Models\ProjectMedia', 'target');
//    }
//
//        1.
//     Parent Model <--> Pivot & Polymorphic <--> Related
//     ProjectMedia <--> ProjectMediaTag     <--> Tag
//
//    public function sample__media(){
//        // Doc link for relationship https://laravel.com/api/6.x/Illuminate/Database/Eloquent/Relations/MorphToMany.html
//        return $this->morphedByMany('App\Models\ProjectMedia','target' , 'project_media_tag',
//                                    'tag_id','target_id','id','id')
//            ->withPivot(['qty','created_at'])
//            ;
//    }
//
//    public function sample__tags()
//    {
//        return $this->morphToMany('App\Models\Tag','target','project_media_tag',
//                                  'target_id','tag_id','id','id')->withPivot(['qty','created_at']);
//        //'target','project_media_tag','target_id','tag_id','id','id'
//    }


    // Parent Model <--> Polymorphic  <--> Related
    // ProjectQuery <--> ProjectMedia <--> Query
    // ^^


    public function mediaResponse()
    {
        // dd(url(config('constants.MEDIA_IMAGE_PATH')));
        return $this->morphOne('App\Models\ProjectMedia', 'target', 'target_type', 'target_id','query_id')
            ->select([
                 "id" ,
                "project_id" ,
                "target_type" ,
                "target_id" ,
                "path" ,
                "created_at"
                     ]);
    }
    //</editor-fold>

}
