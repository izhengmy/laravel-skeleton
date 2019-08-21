<?php

namespace App\Http\Requests\Admin\Admin;

class AdminUpdateRequest extends AdminStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['password'] = 'string|between:8,16';
        $rules['passwordConfirmation'] = 'required_with:password|same:password';

        return $rules;
    }
}
