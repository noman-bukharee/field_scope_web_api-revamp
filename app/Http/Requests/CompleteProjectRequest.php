<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        \Log::debug('rules called');
        return [
            "name" => 'required|string|max:100',
            "address1" => 'required|string|max:100',
            "address2" => 'string|max:100',
//            "state_id" => '',
//            "city_id" => '',
//            "state_name" => '',
//            "city_name" => '',
//            "postal_code" => '',
            "claim_num" => 'nullable|string|max:100',
            "sales_tax" => 'nullable|numeric|min:1',
            "project_status" => '',
            "inspection_date" => 'required',
            "latitude" => 'required|numeric',
            "longitude" => 'required|numeric',
            "customer_email" => 'nullable|email|max:100',
        ];
    }
}
