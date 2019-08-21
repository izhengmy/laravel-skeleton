<?php

namespace App\Models\Filters\Traits;

trait Enabled
{
    /**
     * 是否启用筛选.
     *
     * @param  bool  $value
     * @return $this
     */
    public function enabled($value)
    {
        return $this->where('enabled', (bool) $value);
    }
}
