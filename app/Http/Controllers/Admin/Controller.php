<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * The guard name.
     *
     * @var string
     */
    protected $guardName = 'admin';

    /**
     * The JWTGuard instance.
     *
     * @var \Tymon\JWTAuth\JWTGuard
     */
    protected $jwtGuard;

    /**
     * Controller methods of the throttle middleware should exclude.
     *
     * @var array
     */
    protected $throttleExcepts = [];

    /**
     * Create a new Controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->jwtGuard = auth($this->guardName);
        $this->middleware('throttle:60,1')->except($this->throttleExcepts);
    }
}
