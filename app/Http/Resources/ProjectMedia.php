<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class ProjectMedia extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    //Noman 
    public function toArray($request)
    {
        $mediaUrl = url(config("constants.MEDIA_IMAGE_PATH"))."/";
        $resource = collect($this->resource) ?: $this;
        $response = [
            'id' => $resource['id'],
            // 'meta_id' => $this->whenLoaded('category', function () {
            //     return $this->category ? $this->category->created_at;
            // }),
            'meta' => $this->meta ?? null,
            'project_id' => $resource['project_id'],
            'target_type' => $resource['target_type'],
            'target_id' => $resource['target_id'],
            'path' => (empty($resource['path'])) ? '' : $mediaUrl.$resource['path'],
            'note' => $resource['note'],
            'created_at' => $resource['created_at'],
            'updated_at' => $resource['updated_at'],
            'ref_id' => $resource['ref_id'],
        ];

        if($this->relationLoaded('media_tags_extended')){
            $response['tags'] = Tag::collection(collect($this->resource['media_tags_extended'])) ;
        }else if($this->relationLoaded('tags')){
//            dd($resource['tags']);
            $response['tags'] = Tag::collection(collect($resource['tags'])) ;
        }


        return $response;
    }
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'meta' => [
                'total_media' => ProjectMedia::count(),
            ],
        ];
    }
}