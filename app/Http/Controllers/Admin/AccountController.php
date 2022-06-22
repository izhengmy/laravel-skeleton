<?php

namespace App\Http\Controllers\Admin;

use App\AdminCodes;
use App\Exceptions\AdminBusinessException;
use App\Http\Requests\Admin\Account\PasswordUpdateRequest;
use App\Http\Requests\Admin\Account\ProfileUpdateRequest;
use App\Http\Resources\Admin\Account\ProfileResource;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Create a new AccountController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:'.$this->guardName);
    }

    /**
     * 个人资料.
     *
     * @return \App\Http\Resources\Admin\Account\ProfileResource
     */
    public function profile(): ProfileResource
    {
        /** @var \App\Models\Admin $admin */
        $admin = $this->jwtGuard->user();
        $admin->load('roles');

        return new ProfileResource($admin);
    }

    /**
     * 更新个人资料.
     *
     * @param  \App\Http\Requests\Admin\Account\ProfileUpdateRequest  $request
     * @return \App\Http\Resources\Admin\Account\ProfileResource
     * @throws \App\Exceptions\AdminBusinessException
     */
    public function updateProfile(ProfileUpdateRequest $request): ProfileResource
    {
        /** @var \App\Models\Admin $admin */
        $admin = $this->jwtGuard->user();

        if (Admin::columnValueExists('username', $request->username, $admin, Admin::withTrashed())) {
            throw AdminBusinessException::make(AdminCodes::ADMIN_USERNAME_ALREADY_EXISTS);
        }

        if (Admin::columnValueExists('mobile_number', $request->mobileNumber, $admin, Admin::withTrashed())) {
            throw AdminBusinessException::make(AdminCodes::ADMIN_MOBILE_NUMBER_ALREADY_EXISTS);
        }

        $admin->fill([
            'username' => $request->username,
            'mobile_number' => $request->mobileNumber,
            'real_name' => $request->realName,
        ]);
        $admin->save();

        return new ProfileResource($admin);
    }

    /**
     * 更新密码.
     *
     * @param  \App\Http\Requests\Admin\Account\PasswordUpdateRequest  $request
     * @return void
     * @throws \App\Exceptions\AdminBusinessException
     */
    public function updatePassword(PasswordUpdateRequest $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = $this->jwtGuard->user();

        if (! Hash::check($request->oldPassword, $admin->getAuthPassword())) {
            throw AdminBusinessException::make(AdminCodes::ACCOUNT_OLD_PASSWORD_ERROR);
        }

        $admin->password = $request->newPassword;
        $admin->save();

        http_no_content();
    }
}
