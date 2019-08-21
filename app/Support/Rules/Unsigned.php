<?php

namespace App\Support\Rules;

use Illuminate\Contracts\Validation\Rule;

class Unsigned implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! is_numeric($value)) {
            return false;
        }

        return $value >= 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unsigned');
    }
}
