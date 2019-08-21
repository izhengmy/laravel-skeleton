<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\Menu\MenuCollection;

class AppController extends Controller
{
    /**
     * Create a new AppController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:'.$this->guardName);
    }

    /**
     * 系统菜单.
     *
     * @return \App\Http\Resources\Admin\Menu\MenuCollection
     */
    public function menus(): MenuCollection
    {
        /** @var \App\Models\Admin $admin */
        $admin = $this->jwtGuard->user();
        $menus = $admin->getMenus();

        return new MenuCollection($menus);
    }
}
