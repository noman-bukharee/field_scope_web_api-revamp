<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class Category extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
//        $image_url = ($this->gender == 'female') ? env('BASE_URL') . Config::get('constants.GENERAL_IMAGE_PATH') . 'female.png' : env('BASE_URL') . Config::get('constants.GENERAL_IMAGE_PATH') . 'male.png';
//        $user_exist_image = ($this->user_group_id == 3) ? env('BASE_URL') . '/' . $this->image_url : env('BASE_URL') . Config::get('constants.USER_IMAGE_PATH') . $this->image_url;
//        $user_exist_image = (filter_var($this->image_url, FILTER_VALIDATE_URL)) ? $this->image_url : $user_exist_image;

        $resource = collect($this->resource) ?: $this;
        $response = [
            'id'                    => $resource['id'],
            'category_name'         => $resource['category_name'],
            'company_id'            => $resource['company_id'],
            'category_type'         => $resource['category_type'],
            'parent_id'             => $resource['parent_id'],
            'category_min_quantity' => $resource['category_min_quantity'],
            'has_child'             => $resource['has_child'],
            'get_child'             => [],
//            'media' =>              collect($resource['media'])->transform(function ($media){
//                return new Media($media);
//            }),
        ];

        if (!empty($resource['get_child'])) {

            $response['get_child'] = collect($resource['get_child'])->transform(function ($category) {

                $category['category_name']         = $category['name'];
                $category['category_type']         = $category['type'];
                $category['category_min_quantity'] = $category['min_quantity'];

//                dd($category['photoview_survey']);
//                $category['photoview_survey'] = new Query($category['photoview_survey']);
                return new Category($category);
            });

        }

//        dd();
        $response['category_tags'] = Tag::collection(collect($resource['category_tags']));

        if(!empty($resource['photoview_survey']) ){
            // dd($this->relationLoaded);
            $response['photoview_survey'] = new Query(collect($resource['photoview_survey']));
        }

//        $response['category_tags'] = collect($resource['category_tags'])->transform(function ($tag){
//
//            return new Tag();
//        });
        return $response;
    }
}
