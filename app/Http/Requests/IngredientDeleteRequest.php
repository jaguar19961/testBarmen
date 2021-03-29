<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngredientDeleteRequest extends FormRequest
{
    public function rules() {
        return [
            //
        ];
    }

    public function authorize()
    {
        return !$this->route()->parameter('ingredient')->cocktails()->exists();
    }

    protected function failedAuthorization()
    {
        abort(403, 'You cannot delete ingredient that have cocktails attached on !');
    }
}
