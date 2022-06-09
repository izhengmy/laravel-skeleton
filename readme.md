# Laravel Skeleton

基本 [Laravel](https://github.com/laravel/laravel) 框架开发的通用项目模板。

## 功能模块

### 开发相关

- Laravel Telescope
- Laravel Horizon

### 后台管理系统

- 授权相关
    - 图形验证码
    - 用户名密码登录
    - 短信验证码登录
    - 重置密码（找回密码）
    - 退出登录
- 权限相关
    - 权限管理
    - 角色管理
- 菜单管理
- 管理员管理
- 个人中心
    - 资料修改
    - 密码修改
- 短信发送日志

## 前端支持

### 后台管理系统

[https://github.com/izhengmy/antd-pro-admin-skeleton](https://github.com/izhengmy/antd-pro-admin-skeleton)

## 服务器要求

- Nginx >= 1.15.12
- PHP >= 7.2.0
- MySQL >= 5.7.26
- Redis >= 3.0
- Supervisor
- BCMath PHP 拓展
- Ctype PHP 拓展
- JSON PHP 拓展
- Mbstring PHP 拓展
- OpenSSL PHP 拓展
- PDO PHP 拓展
- Tokenizer PHP 拓展
- XML PHP 拓展
- Imagick PHP 拓展

## 环境搭建/安装

### 基础安装

```bash
$ docker-compose build
$ docker-compose up -d
$ docker-compose exec php /bin/bash
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan jwt:secret
$ php artisan migrate
$ php artisan db:seed
$ curl http://127.0.0.1:8000
```

### Docker 容器连接信息

Docker 容器 | 连接信息
:- | :-
PHP | Host: 127.0.0.1<br>Port: 8000
MySQL | Host: 127.0.0.1<br>Port: 3306<br>Database: laravel<br>User: root/laravel<br>Password: root/laravel
Redis | Host: 127.0.0.1<br>Port: 6379

## 扩展包使用情况

扩展包 | 描述 | 应用场景
:- | :- | :-
[laravel/telescope](https://github.com/laravel/telescope) | Laravel 官方的优雅调试工具 | 开发调试
[laravel/horizon](https://github.com/laravel/horizon) | Laravel 官方的队列管理工具 | 队列调度、监听队列的使用情况
[predis/predis](https://github.com/nrk/predis) | Redis PHP Client 组件 | 连接 Redis 使用
[overtrue/laravel-lang](https://github.com/overtrue/laravel-lang) | Laravel 多语言支持组件 | validation 错误信息本地化
[overtrue/easy-sms](https://github.com/overtrue/easy-sms) | 多网关短信发送组件 | 发送短信验证码
[leonis/easysms-notification-channel](https://github.com/yangliulnn/easysms-notification-channel) | overtrue/easy-sms Laravel 消息通知系统支持 | 扩展 easy-sms
[tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth) | JWT 用户认证组件 | 后台管理员用户登录
[spatie/laravel-permission](https://github.com/spatie/laravel-permission) | RBAC 组件 | 后台管理员权限控制
[mews/captcha](https://github.com/mewebstudio/captcha) | 图形验证码组件 | 登录验证码
[tucker-eric/eloquentfilter](https://github.com/Tucker-Eric/EloquentFilter) | Eloquent 条件查询组件 | 简化 Eloquent 查询代码
[kalnoy/nestedset](https://github.com/lazychaser/laravel-nestedset) | 无限级分类组件 | 后台管理系统菜单无限级分类
[kunr/pangu.php](https://github.com/linclancey/pangu.php) | 中英文空格处理组件 | 处理中英文空格格式

## License

MIT