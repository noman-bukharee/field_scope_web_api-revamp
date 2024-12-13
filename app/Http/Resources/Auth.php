<?php

namespace App\Http\Resources;

use App\Models\UserGenre;
use App\Models\UserProperty;
use App\Models\UserWishlist;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class Auth extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image_url = ($this->gender == 'female')? env('BASE_URL').Config::get('constants.GENERAL_IMAGE_PATH').'female.png': env('BASE_URL').Config::get('constants.GENERAL_IMAGE_PATH').'male.png';
        $user_exist_image = ($this->user_group_id == 3)? env('BASE_URL').'/'.$this->image_url : env('BASE_URL').Config::get('constants.USER_IMAGE_PATH').$this->image_url;
        $user_exist_image = (filter_var($this->image_url, FILTER_VALIDATE_URL))? $this->image_url : $user_exist_image;

        $response = [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'image_url' => (empty($this->image_url)) ? $image_url : $user_exist_image,
            'token' => $this->token,
            //'token_expiry_at' => date('m-d-Y', strtotime($this->token_expiry_at)),
            //'is_subscribed' => \App\Models\User::verifySubscription($this->id, $this->user_group_id, $this->subscription_expiry_date),
            'user_group_id' => $this->company_group_id,
            'user_group' => empty($this->companyGroup) ? 'admin' : $this->companyGroup->title,
            'hover_user' => !empty($this->hover_user_id) ? true : false,
            'device_type' => $this->device_type,
            'device_token' => $this->device_token,
            'device' => $this->device,
            'created_at' => date('m-d-Y', strtotime($this->created_at)),
        ];
        return $response;
    }
}
