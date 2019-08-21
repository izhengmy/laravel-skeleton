<?php

namespace App\Models\Filters\Traits;

trait Successful
{
    /**
     * 是否成功筛选.
     *
     * @param  bool  $value
     * @return $this
     */
    public function successful($value)
    {
        return $this->where('successful', $value);
    }
}
