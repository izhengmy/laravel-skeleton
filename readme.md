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

本地开发环境推荐使用 [laradock](https://github.com/laradock/laradock)。

下文将在假定读者已经安装好了 [laradock](https://github.com/laradock/laradock) 的情况下进行说明。如果您还未安装 [laradock](https://github.com/laradock/laradock)，可以参照 [laradock 官方文档](http://laradock.io/) 进行安装配置。

### php-worker 配置

配置文件存放在 `/path/to/laradock/php-worker/supervisord` 目录下

laravel-skeleton-scheduler.conf（用于启动 Cron Job）

```conf
[program:laravel-skeleton-scheduler]
process_name=%(program_name)s_%(process_num)02d
command=/bin/sh -c "while [ true ]; do (php /var/www/laravel-skeleton/artisan schedule:run --verbose --no-interaction &); sleep 60; done"
autostart=true
autorestart=true
numprocs=1
user=laradock
redirect_stderr=true
```

laravel-skeleton-horizon.conf（用于管理 Laravel Horizon 进程）

```conf
[program:laravel-skeleton-horizon]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laravel-skeleton/artisan horizon
autostart=true
autorestart=true
numprocs=1
user=laradock
redirect_stderr=true
```

### 基础安装

```bash
$ docker-compose up -d nginx mysql php-worker redis
$ docker-compose exec --user=laradock workspace bash
$ git clone https://github.com/zmy96/laravel-skeleton.git
$ cd laravel-skeleton
$ composer install          # 安装依赖
$ cp .env.example .env      # 根据实际情况修改你的配置项
$ php artisan key:generate  # 生成密钥
$ php artisan jwt:secret    # 生成 JWT 密钥
$ php artisan migrate       # 数据库迁移
$ php artisan db:seed       # 数据填充
```

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
