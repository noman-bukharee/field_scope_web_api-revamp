<?php

namespace App\Rules;

use App\Models\Project;
use App\Models\Tag;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ValidAddress implements Rule
{


    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $addrParts = Project::getAddressParts($value);

        $input = [
            'address' => $addrParts['address'],
            'city' => $addrParts['city'],
            'state' => $addrParts['state'],
            'postal_code' => $addrParts['postal_code'],
        ];

        $param_rules = [
            'address' => "required|string",
            'city' => "required|string",
            'state' => "required|string",
            'postal_code' => "required|numeric",
        ];

        $customMessages = [];
        $validator = Validator::make($input, $param_rules, $customMessages);

        \Log::debug('@passes: '.print_r([
            'attribute' => $attribute,
            'fails' => $validator->fails(),
            'errors' => $validator->errors(),
                                  ],1));


        return $validator->fails() ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute doesn\'t have all required components.';
    }
}