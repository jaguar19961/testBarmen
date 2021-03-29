<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CocktailRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:60',
                'unique:cocktails,name,' . $this->segment(3) . ',id,deleted_at,NULL',
                'string'
            ],
            'ingredients' => [
                'required',
                'array',
            ],
            'ingredients.*' => [
                'array',
            ],
            'ingredients.*.position' => [
                'integer'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'ingredients' => 'Ingredients'
        ];
    }

    public function messages()
    {
        return [
            'ingredients.required' => 'You must select at least one ingredient',
            'ingredients.array' => 'Incorrect :attribute format'
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $ingredients = $this->get('ingredients');

        $ingredients = collect($ingredients)
            ->sortBy('position')
            ->values()
            ->mapWithKeys(fn($ingredient, $key) => [
                $ingredient['id'] => [
                    'position' => $key
                ]
            ])
            ->toArray();

        $this->merge([
            'ingredients' => $ingredients
        ]);
    }
}
