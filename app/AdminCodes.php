<?php

namespace App\Codes;

abstract class AdminCodes
{
    // 授权模块
    public const AUTH_FAILED = 40001001;
    public const AUTH_ADMIN_DOES_NOT_EXIST = 40401002;
    public const AUTH_INVALID_SMS_CAPTCHA = 40401003;

    // 权限模块
    public const PERMISSION_NAME_ALREADY_EXISTS = 40002001;
    public const PERMISSION_DOES_NOT_EXIST = 40402002;
    public const PERMISSION_ROLE_NAME_ALREADY_EXISTS = 40002003;
    public const PERMISSION_ROLE_DOES_NOT_EXIST = 40402004;

    // 管理员模块
    public const ADMIN_USERNAME_ALREADY_EXISTS = 40003001;
    public const ADMIN_MOBILE_NUMBER_ALREADY_EXISTS = 40003002;

    // 菜单模块
    public const ADMIN_MENU_PARENT_DOES_NOT_EXIST = 40404001;
    public const ADMIN_MENU_DOES_NOT_EXIST = 40404002;

    // 个人中心
    public const ACCOUNT_OLD_PASSWORD_ERROR = 40005001;

    /**
     * @var array
     */
    public const MESSAGES = [
        self::AUTH_FAILED => '用户名或密码错误',
        self::AUTH_ADMIN_DOES_NOT_EXIST => '管理员不存在',
        self::AUTH_INVALID_SMS_CAPTCHA => '短信验证码错误',

        self::PERMISSION_NAME_ALREADY_EXISTS => '权限名称已经存在',
        self::PERMISSION_DOES_NOT_EXIST => '权限不存在',
        self::PERMISSION_ROLE_NAME_ALREADY_EXISTS => '角色名称已经存在',
        self::PERMISSION_ROLE_DOES_NOT_EXIST => '角色不存在',

        self::ADMIN_USERNAME_ALREADY_EXISTS => '用户名已经存在',
        self::ADMIN_MOBILE_NUMBER_ALREADY_EXISTS => '手机号码已经存在',

        self::ADMIN_MENU_PARENT_DOES_NOT_EXIST => '父级菜单不存在',
        self::ADMIN_MENU_DOES_NOT_EXIST => '菜单不存在',

        self::ACCOUNT_OLD_PASSWORD_ERROR => '旧密码错误',
    ];
}
