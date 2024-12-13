<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class HoverTags extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'company_id' => $this->company_id,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
            'min_quantity' => $this->min_quantity,
            'thumbnail' => $this->thumbnail,
            'order_by' => $this->order_by,
            'created_at' => date('m-d-Y', strtotime($this->created_at)),
        ];
        return $response;
    }
}
