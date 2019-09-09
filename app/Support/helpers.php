<?php

use App\Support\Http;
use Illuminate\Http\JsonResponse;

if (! function_exists('http_success')) {
    /**
     * 返回 HTTP 200 响应.
     *
     * @param  string|null  $message
     * @param  mixed  $data
     * @return \Illuminate\Http\JsonResponse
     */
    function http_success(string $message = null, $data = null): JsonResponse
    {
        $code = 200;

        if (is_null($message)) {
            $message = Http::MESSAGES[$code];
        }

        if (! is_object($data)) {
            $data = (object) $data;
        }

        return response()->json(compact('code', 'message', 'data'), $code);
    }
}

if (! function_exists('http_no_content')) {
    /**
     * 返回 HTTP 204 响应.
     *
     * @return void
     */
    function http_no_content()
    {
        abort(204);
    }
}

if (! function_exists('is_paginate')) {
    /**
     * 是否分页.
     *
     * @return bool
     */
    function is_paginate()
    {
        return (bool) request()->input('paginate', true);
    }
}
