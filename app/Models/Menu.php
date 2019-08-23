<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;
use Override\Laravel\Illuminate\Database\Eloquent\Model;

/**
 * Class Menu.
 *
 * @package App\Models
 * @property int $id
 * @property int $lft
 * @property int $rgt
 * @property int|null $parent_id
 * @property string $path
 * @property string $name
 * @property string $icon
 * @property int $sort
 * @property bool $new_window
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Kalnoy\Nestedset\Collection $children
 * @property \Kalnoy\Nestedset\Collection $ancestors
 * @property \Kalnoy\Nestedset\Collection $descendants
 * @property \Illuminate\Database\Eloquent\Collection $roles
 * @mixin \Eloquent
 */
class Menu extends Model
{
    use NodeTrait;

    /**
     * 资源名称.
     *
     * @var string
     */
    const RESOURCE_NAME = '菜单';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'path',
        'name',
        'icon',
        'sort',
        'new_window',
        'enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'new_window' => 'bool',
        'enabled' => 'bool',
    ];

    /**
     * 关联角色模型.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_menus');
    }

    /**
     * Get the lft key name.
     *
     * @return  string
     */
    public function getLftName()
    {
        return 'lft';
    }

    /**
     * Get the rgt key name.
     *
     * @return  string
     */
    public function getRgtName()
    {
        return 'rgt';
    }

    /**
     * 启用所有父级菜单.
     *
     * @return void
     * @throws \Throwable
     */
    public function enableAncestors()
    {
        $this->load('ancestors');

        DB::transaction(function () {
            $this->ancestors->each(function (Menu $menu) {
                $menu->enabled = true;
                $menu->save();
            });
        });
    }

    /**
     * 禁用所有子孙级菜单.
     *
     * @return void
     * @throws \Throwable
     */
    public function disableDescendants()
    {
        $this->load('descendants');

        DB::transaction(function () {
            $this->descendants->each(function (Menu $menu) {
                $menu->enabled = false;
                $menu->save();
            });
        });
    }

    /**
     * 移除菜单关联的角色（包含所有子孙级菜单）.
     *
     * @return void
     * @throws \Throwable
     */
    public function detachRoles()
    {
        $this->load('descendants.roles');

        DB::transaction(function () {
            $this->roles()->detach();
            $this->descendants->each(function (Menu $menu) {
                $menu->roles()->detach();
            });
        });
    }
}
