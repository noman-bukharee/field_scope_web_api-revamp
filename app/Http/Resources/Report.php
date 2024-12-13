<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class Report extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $baseUrl = url("") . "/";
        $response = [
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'path' => $baseUrl . $this->path,
//            'token' => $this->token,
            'options' => json_decode($this->options),
            'inspector_sign' => !empty($this->inspector_sign) ? $baseUrl . $this->inspector_sign : null,
            'inspector_sign_at' => $this->inspector_sign_at,
            'customer_sign' => !empty($this->customer_sign) ? $baseUrl . $this->customer_sign : null,
            'customer_sign_at' => $this->customer_sign_at,
            'is_signed' => $this->is_signed,
            'created_at' => $this->created_at,
        ];

//        dd("report reouscr",$response);

        return $response;
    }
}
