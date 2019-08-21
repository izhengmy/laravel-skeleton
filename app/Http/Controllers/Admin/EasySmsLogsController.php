<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\EasySmsLog\EasySmsLogCollection;
use App\Models\EasySmsLog;
use Illuminate\Http\Request;

class EasySmsLogsController extends Controller
{
    /**
     * Create a new EasySmsLogsController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:'.$this->guardName);
        $this->middleware('permission:easy-sms-logs.index')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Admin\EasySmsLog\EasySmsLogCollection
     */
    public function index(Request $request): EasySmsLogCollection
    {
        $easySmsLogs = EasySmsLog::filter($request->all())->orderBy('created_at', 'desc')->paginate();

        return new EasySmsLogCollection($easySmsLogs);
    }
}
