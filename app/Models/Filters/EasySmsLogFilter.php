<?php

namespace App\Models\Filters;

use App\Models\Filters\Traits\EndCreatedAt;
use App\Models\Filters\Traits\MobileNumber;
use App\Models\Filters\Traits\StartCreatedAt;
use App\Models\Filters\Traits\Successful;
use EloquentFilter\ModelFilter;

class EasySmsLogFilter extends ModelFilter
{
    use EndCreatedAt;
    use MobileNumber;
    use StartCreatedAt;
    use Successful;
}
