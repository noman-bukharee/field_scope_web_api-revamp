<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class Project extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $image_url = ($this->gender == 'female') ? env('BASE_URL') . Config::get('constants.GENERAL_IMAGE_PATH') . 'female.png' : env('BASE_URL') . Config::get('constants.GENERAL_IMAGE_PATH') . 'male.png';
        $user_exist_image = ($this->user_group_id == 3) ? env('BASE_URL') . '/' . $this->image_url : env('BASE_URL') . Config::get('constants.USER_IMAGE_PATH') . $this->image_url;
        $user_exist_image = (filter_var($this->image_url, FILTER_VALIDATE_URL)) ? $this->image_url : $user_exist_image;


        $resource = collect($this->resource) ?: $this;
        $response = [
            'id' => $this->id,
            'company_id'        => $this->company_id,
            'user_id'           => $this->user_id,
            'name'              => $this->name,
            'address1'          => $this->address1,
            'address2'          => $this->address2,
            'assigned_user_id'  => $this->assigned_user_id,
            'state_id'          => $this->state_id,
            'state_name'        => $this->state_name,
            'city_id'           => $this->city_id,
            'city_name'         => $this->city_name,
            'postal_code'       => $this->postal_code,
            'claim_num'         => $this->claim_num,
            'inspection_date'   => $this->inspection_date,
            'latitude'          => $this->latitude,
            'longitude'         => $this->longitude,
            'customer_email' => $this->customer_email,
            'is_updated' => $this->is_updated,
            'sales_tax' => $this->sales_tax,
            'categories' => !empty($this->categories) > 0 ? $this->categories : NULL,
//            'media'             => !empty($this->project_media) > 0 ? $this->project_media : [] ,
            'project_media' => !empty($this->getSingleMedia) > 0 ? $this->getSingleMedia->toArray() : [],
            'media_tag' => !empty($this->media_tag) > 0 ? $this->media_tag->toArray() : [],
            // 'status_id'         => !empty($this->status_id) ? $this->status_id : NULL, // 1:initiated , 2:completed
            'project_status' => !empty($this->project_status) ? $this->project_status : NULL, // 1:open , 2:closed
            'ref_id' => $this->ref_id,
            'created_at' => $this->created_at->toDateTimeString() ?: null,
            'updated_at' => $this->updated_at->toDateTimeString() ?: null,
            'display_created_at' => $this->display_created_at ?: null,
            'display_updated_at' => $this->display_updated_at ?: null,
        ];

        if($this->relationLoaded('complete_address')){
            $response['state_name'] = $this->complete_address->state_name;
            $response['city_name'] = $this->complete_address->name;
        }else{
            $response['state_name'] = $this->state_name;
            $response['city_name'] = $this->city_name;
        }

        if($this->relationLoaded('assigned_user')){
            $response['assigned_user'] = new UserShort($this->assigned_user);
        }

//        $r = new ResourceCollection(collect($resource['survey']));


//        $response['survey'] = ProjectQuery::collection($this->surveyResponse);

//        dd("surveyResponse",$this->relationLoaded('surveyResponse'), $this->whenLoaded('surveyResponse'));

        if ($this->relationLoaded('project_media')) {
            //Noman
            //$response['media'] = ProjectMedia::collection($this->project_media);
            $response['media'] = $this->project_media->map(function ($media) {
                return array_merge($media->toArray(), [
                    'longitude' => $this->longitude,
                    'latitude' => $this->latitude,
                    'date' => $media->created_at->toDateTimeString(),
                ]);
            });
        }

        if ($this->relationLoaded('surveyResponse')) {
            $response['survey'] = ProjectQuery::collection($this->surveyResponse);
        }

        if ($this->relationLoaded('report')) {
            $response['report'] = new Report($this->report);
        }
        if ($this->relationLoaded('hover')) {
            $response['hover'] = new HoverJob($this->hover);
        }


//        dd($this->categories);
        if (FALSE && !empty($this->categories) > 0 && is_array($this->categories)) {
            foreach ($this->categories as $key => $item) {
                $response['categories'][$key] = collect($this->resource->categories[$key])->transform(
                    function ($category) {
                        return new Category($category);
                    }
                );
                //$response['categories'][$key] = new CategoryCollection($cat);

//                $response['categories'][$key] = Category::collection(collect($item));
            }
        }

        return $response;
    }
}
