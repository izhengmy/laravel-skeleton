<?php

namespace Override\Laravel\Illuminate\Http\Resources\Json;

use App\Support\Http;
use Illuminate\Database\Eloquent\Model;

trait With
{
    /**
     * The additional message that should be added to the top-level resource array.
     *
     * @var string
     */
    protected $message;

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with(/** @noinspection PhpUnusedParameterInspection */$request)
    {
        $wasRecentlyCreated = $this->resource instanceof Model && $this->resource->wasRecentlyCreated;
        $code = $wasRecentlyCreated ? 201 : 200;

        $this->with = [
            'code' => $code,
            'message' => $this->message ?: Http::MESSAGES[$code],
        ];

        return $this->with;
    }

    /**
     * With the additional message that should be added to the top-level resource array.
     *
     * @param  string  $message
     * @return static
     */
    public function withMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }
}
