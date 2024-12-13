<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Config;

class CategoryByType extends Resource
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

//        dd('category_tags',$this->resource);

        $response = [];
        foreach ($this->resource AS $key => $item){

            if(!empty($item['category_type']) && $item['category_type'] == 3){
                $response[$key] = Category::collection(collect([$item]))[0];
            }else{
                $response[$key] = Category::collection(collect($item));
            }
        }

//        $response = [
//            'id'                    => $resource['id'],
//            'category_name'         => $resource['category_name'],
//            'company_id'            => $resource['company_id'],
//            'category_type'         => $resource['category_type'],
//            'parent_id'             => $resource['parent_id'],
//            'category_min_quantity' => $resource['category_min_quantity'],
//            'thumbnail'             => $resource['thumbnail'],
//            'order_by'              => $resource['order_by'],
//            'user_group_title'      => $this->user_group_title,
//            'has_child'             => $resource['has_child'],
//            'get_child'             => [],
//            'media' =>         collect($resource['media'])->transform(function ($media){
//                return new Media($media);
//            }),
////            'created_at' => date('m-d-Y', strtotime($this->created_at)),
//        ];
////        dump('get_child',$resource['get_child']);
//
//        if (!empty($resource['get_child'])) {
//
//            $response['get_child'] = collect($resource['get_child'])->transform(function ($category) {
//                return new Category($category);
//            });
//
////            jsond($response);
//        }
//
////        $response['category_tags'] = collect($resource['category_tags'])->transform(function ($tag){
////            return new Tag($tag);
////        });
        return $response;
    }
}
