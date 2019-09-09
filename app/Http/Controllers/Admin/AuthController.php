<?php

namespace App\Http\Controllers\Admin;

use App\Codes\AdminCodes;
use App\Exceptions\AdminBusinessException;
use App\Http\Requests\Admin\Auth\PasswordLoginRequest;
use App\Http\Requests\Admin\Auth\ResetPasswordRequest;
use App\Http\Requests\Admin\Auth\SmsCaptchaLoginRequest;
use App\Http\Requests\Admin\Auth\SmsCaptchaRequest;
use App\Models\Admin;
use App\Support\Facades\SmsCaptcha;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @var string
     */
    private const SMS_CAPTCHA_TYPE = 'admin_auth';

    /**
     * Controller methods of the throttle middleware should exclude.
     *
     * @var array
     */
    protected $throttleExcepts = ['smsCaptcha'];

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('throttle:5,1')->only('smsCaptcha');
    }

    /**
     * 获取图形验证码.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function captcha(): JsonResponse
    {
        return http_success('获取成功', [
            'captcha' => app('captcha')->create('auth', true),
        ]);
    }

    /**
     * 用户名密码登录.
     *
     * @param  \App\Http\Requests\Admin\Auth\PasswordLoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AdminBusinessException
     */
    public function passwordLogin(PasswordLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');
        $credentials['enabled'] = true;

        if (! $token = $this->jwtGuard->attempt($credentials)) {
            throw AdminBusinessException::make(AdminCodes::AUTH_FAILED);
        }

        return $this->responseWithToken($token);
    }

    /**
     * 获取短信验证码.
     *
     * @param  \App\Http\Requests\Admin\Auth\SmsCaptchaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AdminBusinessException
     */
    public function smsCaptcha(SmsCaptchaRequest $request): JsonResponse
    {
        $mobileNumber = $request->mobileNumber;

        $admin = Admin::findByMobileNumber($mobileNumber, true);

        if (empty($admin)) {
            throw AdminBusinessException::make(AdminCodes::AUTH_ADMIN_DOES_NOT_EXIST);
        }

        $code = $admin->sendAuthSmsCaptchaNotification();

        $data = app()->environment('local') ? ['code' => $code->getValue()] : [];

        return http_success('获取成功', $data);
    }

    /**
     * 短信验证码登录.
     *
     * @param  \App\Http\Requests\Admin\Auth\SmsCaptchaLoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AdminBusinessException
     */
    public function smsCaptchaLogin(SmsCaptchaLoginRequest $request): JsonResponse
    {
        $admin = $this->retrieveAdminBySmsCaptcha($request->mobileNumber, $request->smsCaptcha);
        $token = $this->jwtGuard->login($admin);

        return $this->responseWithToken($token);
    }

    /**
     * 退出登录.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $this->jwtGuard->logout();
        } catch (Exception $e) {
        } finally {
            return http_success('退出登录成功');
        }
    }

    /**
     * Response With Token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithToken(string $token): JsonResponse
    {
        /** @var \Tymon\JWTAuth\Factory $factory */
        /** @noinspection PhpUndefinedMethodInspection */
        $factory = $this->jwtGuard->factory();

        return http_success('登录成功', [
            'token' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => $factory->getTTL() * 60,
        ]);
    }

    /**
     * 重置密码.
     *
     * @param  \App\Http\Requests\Admin\Auth\ResetPasswordRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AdminBusinessException
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $admin = $this->retrieveAdminBySmsCaptcha($request->mobileNumber, $request->smsCaptcha);
        $admin->password = $request->password;
        $admin->save();

        return http_success();
    }

    /**
     * 通过短信验证码取回管理员.
     *
     * @param  string  $mobileNumber
     * @param  string  $smsCaptcha
     * @return \App\Models\Admin
     * @throws \App\Exceptions\AdminBusinessException
     */
    protected function retrieveAdminBySmsCaptcha(string $mobileNumber, string $smsCaptcha): Admin
    {
        if (! SmsCaptcha::check($mobileNumber, $smsCaptcha, self::SMS_CAPTCHA_TYPE)) {
            throw AdminBusinessException::make(AdminCodes::AUTH_INVALID_SMS_CAPTCHA);
        }

        SmsCaptcha::forget($mobileNumber, $smsCaptcha, self::SMS_CAPTCHA_TYPE);

        $admin = Admin::findByMobileNumber($mobileNumber, true);

        if (empty($admin)) {
            throw AdminBusinessException::make(AdminCodes::AUTH_ADMIN_DOES_NOT_EXIST);
        }

        return $admin;
    }
}
