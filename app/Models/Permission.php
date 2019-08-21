<?php

namespace App\Models;

use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * Class Permission.
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $cn_name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @mixin \Eloquent
 */
class Permission extends BasePermission
{
    /**
     * 资源名称.
     *
     * @var string
     */
    const RESOURCE_NAME = '权限';

    /**
     * 判断权限名称是否已经存在.
     *
     * @param  string  $name
     * @param  string  $guardName
     * @param  mixed  $without
     * @return bool
     */
    public static function nameExists(string $name, string $guardName, $without = null): bool
    {
        /** @var static|null $target */
        try {
            $target = static::findByName($name, $guardName);
        } catch (PermissionDoesNotExist $e) {
            $target = null;
        }

        $exists = mb_strtoupper(optional($target)->name) == mb_strtoupper($name);

        /** @var static|null $without */
        if (! is_null($without) && $without->is($target)) {
            $exists = false;
        }

        return $exists;
    }
}
