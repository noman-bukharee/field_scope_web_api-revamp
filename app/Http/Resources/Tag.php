<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class Tag extends Resource
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
            'id'            => $this->resource['id'],
            'name' => $this->resource['name'],
            'media_id' => $this->when(!empty($this->resource['target_id']),$this->resource['target_id']) ,
            'category_id' => $this->when(!empty($this->resource['ref_id']),$this->resource['ref_id']),
//            'ref_type' => $this->resource['ref_type'],
            'quantity' => (!empty($this->resource['qty'])) ? $this->resource['qty'] : null,
            'has_qty' => !empty($this->resource['has_qty']) ? true : false,
            'is_required' => !empty($this->resource['is_required']) ? true : false,
//            'uom_id' => $this->resource['uom_id'],
//            'annotation' => $this->resource['annotation'],
//            'hover_field_type_id' => $this->hover_field_type_id,
//            'hover_field_type_slug' => $this->field_type_slug,
//            'hover_field_id' => $this->hover_field_id,
//            'hover_field_name' => $this->hover_field_name,
//
//            'hover_value' => $this->hover_value,
//            'hover_data_title' => $this->hover_data_title,
//            'hover_data' => $this->hover_data,
//            'created_at' => !empty($this->created_at) ? date('Y-m-d H:i:s', $this->created_at,'') : null,
        ];

        return $response;
    }
}
