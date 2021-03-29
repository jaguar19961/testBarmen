<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngredientRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:ingredients,name,' . $this->segment(3) . ',id,deleted_at,NULL',
                'string',
                'max:60'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0,1',
                'max:50'
            ],
            'is_alcohol' => [
                'required',
                'boolean'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'price' => 'Price'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
