<?php

namespace App\Models;

use App\Models\Traits\ColumnValueExists;
use App\Notifications\Admin\AuthSmsCaptcha;
use App\Support\Facades\SmsCaptcha;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Kalnoy\Nestedset\Collection as NestedsetCollection;
use Overtrue\EasySms\PhoneNumber;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Admin.
 *
 * @package App\Models
 * @property int $id
 * @property string $username
 * @property string $mobile_number
 * @property string $password
 * @property string $real_name
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection $roles
 * @property \Illuminate\Database\Eloquent\Collection $permissions
 * @method static \Illuminate\Database\Eloquent\Builder filter($query, array $input = [], $filter = null)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;
    use ColumnValueExists;
    use Filterable;

    /**
     * 资源名称.
     *
     * @var string
     */
    const RESOURCE_NAME = '管理员';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'mobile_number',
        'password',
        'real_name',
        'enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'bool',
    ];

    /**
     * The guard name of spatie/permission package.
     *
     * @var string
     */
    protected $guard_name = 'admin';

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'roles' => $this->getRoleNames(),
            'permissions' => $this->getPermissionsViaRoles()->pluck('name')->all(),
        ];
    }

    /**
     * Set the password attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute(string $value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the notification routing information for the easy sms driver.
     *
     * @param  mixed  $notification
     * @return \Overtrue\EasySms\PhoneNumber
     */
    public function routeNotificationForEasySms(/** @noinspection PhpUnusedParameterInspection */ $notification)
    {
        return new PhoneNumber($this->mobile_number, 86);
    }

    /**
     * 发送认证短信验证码.
     *
     * @return \App\Support\SmsCaptcha\Code
     */
    public function sendAuthSmsCaptchaNotification()
    {
        $code = SmsCaptcha::generate($this->mobile_number, 'admin_auth');

        $this->notify(new AuthSmsCaptcha($code->getValue()));

        return $code;
    }

    /**
     * 通过手机号码获取.
     *
     * @param  string  $mobileNumber
     * @param  bool|null  $enabled
     * @return \App\Models\Admin|null
     */
    public static function findByMobileNumber(string $mobileNumber, $enabled = null): ?Admin
    {
        return self::where('mobile_number', $mobileNumber)
            ->when(is_bool($enabled), function ($query) use ($enabled) {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->where('enabled', $enabled);
            })
            ->first();
    }

    /**
     * 获取用户菜单.
     *
     * @return \Kalnoy\Nestedset\Collection
     */
    public function getMenus(): NestedsetCollection
    {
        $this->load('roles.menus');

        $menus = $this->roles->map(function (Role $role) {
            return $role->menus->filter(function (Menu $menu) {
                return $menu->enabled;
            })->sortByDesc('sort')->all();
        })->flatten();
        $menus = (new NestedsetCollection($menus))->toTree();

        return $menus->filter(function (Menu $menu) {
            return $menu->isLeaf() || $menu->children->isNotEmpty();
        });
    }
}
