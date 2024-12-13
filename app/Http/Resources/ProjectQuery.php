<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class ProjectQuery extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = collect($this->resource) ?: $this;
        $response = [
            'id'                => $resource['id'],
            'project_id'        => $resource['project_id'],
            'query_id'          => $resource['query_id'],

//            'user_response'     => $resource['response']?: NULL, // Will be defined below
            'created_at'        => $resource['created_at'],

            /** Below are related model fields (RN expected to be joined) and depend on how you bind them via relation or join */
//            'options'           => $resource['options'],
            'type'              => $resource['type'],
            'category_id'       => $resource['category_id'],
            'photo_view_id'       => $resource['photo_view_id'],
        ];


//        $q = new Query($this->resource['survey']);
//         dd($q->options); // This works
        if($this->relationLoaded('survey')){
            /** This relation gets called from app/Models/ProjectQuery.php*/

            /** Below are related model fields (RN expected to be get from relationship)*/
            $response['options'] =  $resource['survey']['options'];
            $response['type'] =  $resource['survey']['type'];
            $response['category_id'] = $resource['survey']['category_id'];

            if (in_array($resource['survey']['type'], ['checkbox', 'radio'])) {
                $responseArr = explode(',',$resource['response']);
                $response['response'] = collect($response['options'])->map(function ($el) use($responseArr){
                    $el['is_selected'] = in_array($el['title'],$responseArr);
                    return $el ;
                });
            }
        }

        /** For this work model App\Models\ProjectQuery.php should be joined with App\Models\Query.php */
        if (in_array($this->type, ['checkbox', 'radio']) && $this->response instanceof Collection){
            $response['response'] = $this->response;
        }else if (in_array($this->type, ['sign'])){
            $response['response'] = url(config("constants.MEDIA_IMAGE_PATH").$resource['media_response']['path']);
        }

        return $response;
    }

}
