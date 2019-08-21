<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SmsCaptcha.
 *
 * @package App\Support\SmsCaptcha\Facades
 * @method static \App\Support\SmsCaptcha\Code generate(string $mobileNumber, $type = 'default', $minutes = 10)
 * @method static bool check(string $mobileNumber, string $codeValue, string $type = 'default')
 * @method static \App\Support\SmsCaptcha\Code retrieve(string $mobileNumber, string $codeValue, $type = 'default')
 * @method static void forget(string $mobileNumber, string $codeValue, $type = 'default')
 */
class SmsCaptcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return \App\Support\SmsCaptcha\SmsCaptcha::class;
    }
}
