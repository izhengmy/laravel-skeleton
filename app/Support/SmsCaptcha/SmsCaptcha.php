<?php

namespace App\Support\SmsCaptcha;

use Illuminate\Cache\CacheManager;

class SmsCaptcha
{
    /**
     * The cache manager instance.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Create a new SmsCaptcha instance.
     *
     * @param  \Illuminate\Cache\CacheManager  $cache
     * @return void
     */
    public function __construct(CacheManager $cache)
    {
        $this->cache = $cache;
    }

    /**
     * 生成短信验证码.
     *
     * @param  string  $mobileNumber
     * @param  string  $type
     * @param  int  $minutes
     * @return \App\Support\SmsCaptcha\Code
     */
    public function generate(string $mobileNumber, $type = 'default', $minutes = 10): Code
    {
        $codeValue = (string) mt_rand(100000, 999999);

        $entity = new Code($codeValue, $type, true);

        $this->cache->add($this->cacheKey($mobileNumber, $codeValue, $type), 1, $minutes * 60);

        return $entity;
    }

    /**
     * 检查验证码是否有效.
     *
     * @param  string  $mobileNumber
     * @param  string  $codeValue
     * @param  string  $type
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function check(string $mobileNumber, string $codeValue, string $type = 'default')
    {
        $code = $this->retrieve($mobileNumber, $codeValue, $type);

        return $code->isAvailable();
    }

    /**
     * 取回验证码.
     *
     * @param  string  $mobileNumber
     * @param  string  $codeValue
     * @param  string  $type
     * @return \App\Support\SmsCaptcha\Code
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function retrieve(string $mobileNumber, string $codeValue, $type = 'default'): Code
    {
        $available = $this->cache->has($this->cacheKey($mobileNumber, $codeValue, $type));

        return new Code($codeValue, $type, $available);
    }

    /**
     * 删除验证码.
     *
     * @param  string  $mobileNumber
     * @param  string  $codeValue
     * @param  string  $type
     * @return void
     */
    public function forget(string $mobileNumber, string $codeValue, $type = 'default')
    {
        $this->cache->forget($this->cacheKey($mobileNumber, $codeValue, $type));
    }

    /**
     * 拼接缓存 Key.
     *
     * @param  string  $mobileNumber
     * @param  string  $codeValue
     * @param  string  $type
     * @return string
     */
    protected function cacheKey(string $mobileNumber, string $codeValue, string $type): string
    {
        return "sms_captcha:{$type}:{$mobileNumber}:{$codeValue}";
    }
}
