<?php

namespace App\Models\Filters\Traits;

trait Username
{
    /**
     * 用户名筛选.
     *
     * @param  string  $value
     * @return $this
     */
    public function username($value)
    {
        return $this->where('username', $value);
    }
}
