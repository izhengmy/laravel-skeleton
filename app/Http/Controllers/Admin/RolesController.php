<?php

namespace App\Http\Controllers\Admin;

use App\Codes\AdminCodes;
use App\Exceptions\AdminBusinessException;
use App\Http\Requests\Admin\Permission\RoleStoreRequest;
use App\Http\Requests\Admin\Permission\RoleUpdateRequest;
use App\Http\Resources\Admin\Permission\RoleCollection;
use App\Http\Resources\Admin\Permission\RoleResource;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class RolesController extends Controller
{
    /**
     * Create a new RolesController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:'.$this->guardName);
        $this->middleware('permission:roles.index')->only('index');
        $this->middleware('permission:roles.store')->only('store');
        $this->middleware('permission:roles.show')->only('show');
        $this->middleware('permission:roles.update')->only('update');
        $this->middleware('permission:roles.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\Admin\Permission\RoleCollection
     */
    public function index(): RoleCollection
    {
        $query = Role::with('permissions', 'menus')->where('guard_name', $this->guardName);
        $roles = is_paginate() ? $query->paginate() : $query->get();

        return new RoleCollection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\Permission\RoleStoreRequest  $request
     * @return \App\Http\Resources\Admin\Permission\RoleResource
     * @throws \Throwable
     */
    public function store(RoleStoreRequest $request): RoleResource
    {
        return DB::transaction(function () use ($request) {
            try {
                $role = Role::create([
                    'name' => $request->name,
                    'cn_name' => $request->cnName,
                    'guard_name' => $this->guardName,
                ]);
            } catch (RoleAlreadyExists $e) {
                throw AdminBusinessException::make(AdminCodes::PERMISSION_ROLE_NAME_ALREADY_EXISTS);
            }

            try {
                $role->syncPermissions($request->permissionIds);
            } catch (PermissionDoesNotExist $e) {
                throw AdminBusinessException::make(AdminCodes::PERMISSION_DOES_NOT_EXIST);
            }

            $role->syncMenus($request->menuIds);

            return new RoleResource($role);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\Admin\Permission\RoleResource
     */
    public function show(int $id): RoleResource
    {
        $role = Role::with('permissions', 'menus')->findOrFail($id);

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\Permission\RoleUpdateRequest  $request
     * @param  int  $id
     * @return \App\Http\Resources\Admin\Permission\RoleResource
     * @throws \App\Exceptions\AdminBusinessException
     * @throws \Throwable
     */
    public function update(RoleUpdateRequest $request, int $id): RoleResource
    {
        $role = Role::findOrFail($id);

        if (Role::nameExists($request->name, $this->guardName, $role)) {
            throw AdminBusinessException::make(AdminCodes::PERMISSION_ROLE_NAME_ALREADY_EXISTS);
        }

        return DB::transaction(function () use ($role, $request) {
            $role->fill([
                'name' => $request->name,
                'cn_name' => $request->cnName,
            ]);
            $role->save();

            try {
                $role->syncPermissions($request->permissionIds);
            } catch (PermissionDoesNotExist $e) {
                throw AdminBusinessException::make(AdminCodes::PERMISSION_DOES_NOT_EXIST);
            }

            $role->syncMenus($request->menuIds);

            return new RoleResource($role);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        http_no_content();
    }
}
