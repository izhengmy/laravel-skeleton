<?php

namespace App\Http\Controllers\Admin;

use App\Codes\AdminCodes;
use App\Exceptions\AdminBusinessException;
use App\Http\Requests\Admin\Menu\MenuStoreRequest;
use App\Http\Requests\Admin\Menu\MenuUpdateRequest;
use App\Http\Resources\Admin\Menu\MenuCollection;
use App\Http\Resources\Admin\Menu\MenuResource;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenusController extends Controller
{
    /**
     * Create a new MenusController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:'.$this->guardName);
        $this->middleware('permission:menus.index')->only('index');
        $this->middleware('permission:menus.store')->only('store');
        $this->middleware('permission:menus.show')->only('show');
        $this->middleware('permission:menus.update')->only('update');
        $this->middleware('permission:menus.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\Admin\Menu\MenuCollection
     */
    public function index(): MenuCollection
    {
        /** @var \Kalnoy\Nestedset\Collection $collection */
        $collection = Menu::orderBy('sort', 'desc')->get();
        $menus = $collection->toTree();

        return new MenuCollection($menus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\Menu\MenuStoreRequest  $request
     * @return \App\Http\Resources\Admin\Menu\MenuResource
     * @throws \App\Exceptions\AdminBusinessException
     * @throws \Throwable
     */
    public function store(MenuStoreRequest $request): MenuResource
    {
        if (! is_null($request->parentId) && empty(Menu::find($request->parentId))) {
            throw AdminBusinessException::make(AdminCodes::ADMIN_MENU_PARENT_DOES_NOT_EXIST);
        }

        return DB::transaction(function () use ($request) {
            $menu = new Menu([
                'parent_id' => $request->parentId,
                'path' => $request->path,
                'name' => $request->name,
                'icon' => (string) $request->icon,
                'sort' => $request->sort,
                'new_window' => $request->newWindow,
                'enabled' => $request->enabled,
            ]);
            $menu->save();
            $menu->enabled ? $menu->enableAncestors() : $menu->disableDescendants();

            return new MenuResource($menu);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\Admin\Menu\MenuResource
     */
    public function show(int $id): MenuResource
    {
        $menu = Menu::findOrFail($id);

        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\Menu\MenuUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\AdminBusinessException
     * @throws \Throwable
     */
    public function update(MenuUpdateRequest $request, int $id)
    {
        $menu = Menu::findOrFail($id);

        if (! is_null($request->parentId) && empty(Menu::find($request->parentId))) {
            throw AdminBusinessException::make(AdminCodes::ADMIN_MENU_PARENT_DOES_NOT_EXIST);
        }

        return DB::transaction(function () use ($menu, $request) {
            $menu->fill([
                'parent_id' => $request->parentId,
                'path' => $request->path,
                'name' => $request->name,
                'icon' => (string) $request->icon,
                'sort' => $request->sort,
                'new_window' => $request->newWindow,
                'enabled' => $request->enabled,
            ]);
            $menu->save();
            $menu->enabled ? $menu->enableAncestors() : $menu->disableDescendants();

            return new MenuResource($menu);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     * @throws \Throwable
     */
    public function destroy(int $id)
    {
        $menu = Menu::findOrFail($id);

        DB::transaction(function () use ($menu) {
            $menu->delete();
        });

        http_no_content();
    }
}
