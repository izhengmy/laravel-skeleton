<?php

namespace App\Models;

use App\Codes\AdminCodes;
use App\Exceptions\AdminBusinessException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role as BaseRole;

/**
 * Class Role.
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $cn_name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Database\Eloquent\Collection $permissions
 * @property \Illuminate\Database\Eloquent\Collection $menus
 * @mixin \Eloquent
 */
class Role extends BaseRole
{
    /**
     * 资源名称.
     *
     * @var string
     */
    const RESOURCE_NAME = '角色';

    /**
     * 关联菜单模型.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'role_menus');
    }

    /**
     * 判断角色名称是否已经存在.
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
        } catch (RoleDoesNotExist $e) {
            $target = null;
        }

        $exists = mb_strtoupper(optional($target)->name) == mb_strtoupper($name);

        /** @var static|null $without */
        if (! is_null($without) && $without->is($target)) {
            $exists = false;
        }

        return $exists;
    }

    /**
     * 同步菜单.
     *
     * @param  mixed  ...$menus
     * @return $this
     * @throws \Throwable
     */
    public function syncMenus(...$menus)
    {
        if (! $this->exists) {
            return $this;
        }

        $allMenus = Menu::with('ancestors')->get()->keyBy('id');

        $menuIds = collect($menus)
            ->flatten()
            ->filter(function ($menu) {
                return $menu instanceof Menu || is_numeric($menu);
            })
            ->map(function ($menu) use ($allMenus) {
                /** @var \App\Models\Menu $menu */
                $id = $menu instanceof Menu ? $menu->getKey() : $menu;

                if (! $allMenus->has($id)) {
                    throw AdminBusinessException::make(AdminCodes::ADMIN_MENU_DOES_NOT_EXIST);
                }

                $menu = $allMenus->get($id);

                return $menu->ancestors->merge([$menu]);
            })
            ->flatten()
            ->pluck('id')
            ->unique()
            ->sort()
            ->values()
            ->all();

        return DB::transaction(function () use ($menuIds) {
            $this->menus()->sync($menuIds);
            $this->load('menus');

            return $this;
        });
    }
}
