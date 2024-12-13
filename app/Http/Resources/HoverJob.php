<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class HoverJob extends Resource
{
    function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $baseUrl = url("") . "/uploads/hover/";
        $response = [
            'job_id' => $this->job_id,
            'report_url' => $baseUrl.$this->file_path,
            'response_ready' => !empty($this->json_response),
            'state' => $this->state,
            /*'project_id' => $this->project_id,
            'project_ref_id' => $this->project_ref_id,*/
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString()
        ];

        return $response;
    }
}
