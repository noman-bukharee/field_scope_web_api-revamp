<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class Media extends Resource
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
            'id' => $resource['id'],
            'media_type' => $resource['media_type'],
            'path' => (empty($resource['path'])) ? '' : env('BASE_URL').Config::get('constants.MEDIA_IMAGE_PATH').$resource['path'],
            ];

        if(!empty($this->resource['media_tags_extended'])){
            $response['tags'] = Tag::collection(collect($this->resource['media_tags_extended'])) ;
        }

        return $response;
    }
}
