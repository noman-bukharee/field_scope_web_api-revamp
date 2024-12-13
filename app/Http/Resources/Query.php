<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Query extends Resource
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
            'type'              => $resource['type'],
            'query'             => $resource['query'],
            'category_id'       => $resource['category_id'],
            'photo_view_id'     => $resource['photo_view_id'],
            'options'           => $resource['options'],
            'is_required'       => $resource['is_required'] ? true : false ,
            'created_at'        => $resource['created_at'],
        ];

//        dump("app/Http/Resources/Query.php ",$resource,$response);

        return $response;
    }
}
