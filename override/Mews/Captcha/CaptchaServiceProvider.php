<?php

namespace Override\Mews\Captcha;

use Mews\Captcha\CaptchaServiceProvider as ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration files
        $this->publishes([
            $this->configPath() => config_path('captcha.php')
        ], 'config');

        // Validator extensions
        /** @noinspection PhpUndefinedMethodInspection */
        $this->app['validator']->extend(
            'captcha',
            function (/** @noinspection PhpUnusedParameterInspection */ $attribute, $value, $parameters) {
                return captcha_check($value);
            }
        );

        // Validator extensions
        /** @noinspection PhpUndefinedMethodInspection */
        $this->app['validator']->extend(
            'captcha_api',
            function (/** @noinspection PhpUnusedParameterInspection */ $attribute, $value, $parameters) {
                return captcha_api_check($value, $parameters[0]);
            }
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge configs
        $this->mergeConfigFrom($this->configPath(), 'captcha');

        // Bind captcha
        $this->app->bind('captcha', function ($app) {
            return (new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            ))->cache($this->app->get('cache'));
        });
    }

    /**
     * Get the config path.
     *
     * @return bool|string
     */
    protected function configPath()
    {
        return realpath(app_path('../vendor/mews/captcha/config/captcha.php'));
    }
}
