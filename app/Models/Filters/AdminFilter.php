<?php

namespace App\Models\Filters;

use App\Models\Filters\Traits\Enabled;
use App\Models\Filters\Traits\MobileNumber;
use App\Models\Filters\Traits\Username;
use EloquentFilter\ModelFilter;

class AdminFilter extends ModelFilter
{
    use Enabled;
    use MobileNumber;
    use Username;

    /**
     * Array of method names that should not be called.
     *
     * @var array
     */
    protected $blacklist = [
        'mobileNumber',
        'username',
    ];

    /**
     * 关键字筛选.
     *
     * @param  string  $value
     * @return $this
     */
    public function keyword($value)
    {
        $key = $this->input('key');

        switch ($key) {
            case 'username':
                return $this->username($value);
            case 'mobileNumber':
                return $this->mobileNumber($value);
            default:
                return $this;
        }
    }
}
