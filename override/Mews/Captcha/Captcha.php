<?php

namespace Override\Mews\Captcha;

use Illuminate\Cache\CacheManager;
use Mews\Captcha\Captcha as BaseCaptcha;

class Captcha extends BaseCaptcha
{
    /**
     * The api captcha cache key.
     *
     * @var string
     */
    private const API_CACHE_KEY = 'captcha_api:';

    /**
     * The cache manager instance.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Set a cache manager instance.
     *
     * @param  \Illuminate\Cache\CacheManager  $cache
     * @return $this
     */
    public function cache(CacheManager $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Create captcha image.
     *
     * @param  string  $config
     * @param  boolean  $api
     * @return \Intervention\Image\ImageManager
     */
    public function create($config = 'default', $api = false)
    {
        $result = parent::create($config, $api);

        if ($api) {
            $cacheKey = self::API_CACHE_KEY.$result['key'];
            $ttl = $this->config->get('captcha.api_expire') * 60;
            $this->cache->put($cacheKey, 1, $ttl);
        }

        return $result;
    }

    /**
     * Captcha check.
     *
     * @param  string  $value
     * @param  string  $key
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function check_api($value, $key)
    {
        $cacheKey = self::API_CACHE_KEY.$key;

        if (! $this->cache->has($cacheKey)) {
            return false;
        }

        $result = parent::check_api($value, $key);

        if ($result) {
            $this->cache->forget($cacheKey);
        }

        return $result;
    }
}
