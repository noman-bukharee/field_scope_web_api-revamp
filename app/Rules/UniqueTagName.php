<?php

namespace App\Rules;

use App\Models\Tag;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class UniqueTagName implements Rule
{

    private $request , $categoryType, $ignoreId;
    public function __construct(Request $request, $categoryType = 1, $ignoreId = null)
    {
        $this->request = $request;
        $this->categoryType = $categoryType;
        $this->ignoreId = $ignoreId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes( $attribute, $value)
    {

        $tag = Tag::
            join('category AS c','c.id','tag.ref_id')
        ->where(['tag.name' => $value , 'ref_type' => 'category' ,
                    'tag.company_id' => $this->request['company_id'],
                    'c.type' => $this->categoryType,
                ]);
        if(!empty($this->ignoreId)){
            $tag->where('tag.id','<>',$this->ignoreId);
        }

        $count = $tag->count();

        return ($count > 0) ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The tag :attribute is already taken.';
    }
}