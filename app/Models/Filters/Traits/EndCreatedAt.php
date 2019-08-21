<?php

namespace App\Models\Filters\Traits;

trait EndCreatedAt
{
    /**
     * 结束创建时间筛选.
     *
     * @param  string  $value
     * @return $this
     */
    public function endCreatedAt($value)
    {
        return $this->where('created_at', '<=', $value);
    }
}
