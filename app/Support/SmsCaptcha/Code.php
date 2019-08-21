<?php

namespace App\Support\SmsCaptcha;

use Illuminate\Contracts\Support\Arrayable;

class Code implements Arrayable
{
    /**
     * 短信验证码.
     *
     * @var string
     */
    protected $value;

    /**
     * 短信验证码类型.
     *
     * @var string
     */
    protected $type;

    /**
     * 是否可用.
     *
     * @var bool
     */
    protected $available;

    /**
     * Create a new SmsCaptchaEntity instance.
     *
     * @param  string  $value
     * @param  string  $type
     * @param  bool  $available
     * @return void
     */
    public function __construct(string $value, string $type, bool $available)
    {
        $this->value = $value;
        $this->type = $type;
        $this->available = $available;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->available;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'type' => $this->type,
            'available' => $this->available,
        ];
    }
}
