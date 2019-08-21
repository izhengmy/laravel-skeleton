<?php

namespace App\Models\Filters\Traits;

trait MobileNumber
{
    /**
     * 手机号码筛选.
     *
     * @param  string  $value
     * @return $this
     */
    public function mobileNumber($value)
    {
        return $this->where('mobile_number', $value);
    }
}
