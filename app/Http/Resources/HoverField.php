<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class HoverField extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'name' => ucfirst($this->name),
            'hover_type_id' => $this->hover_type_id,
//            'method' => $this->method,
//            'params' => $this->params,
//            'config_path' => $this->config_path,
            'created_at' => $this->created_at->toDateTimeString(),
//            'updated_at' => $this->updated_at,
//            'deleted_at' => $this->deleted_at,
        ];

        return $response;
    }
}
