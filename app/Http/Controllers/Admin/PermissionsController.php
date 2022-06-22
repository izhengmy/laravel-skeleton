<?php

namespace App\Http\Controllers\Admin;

use App\AdminCodes;
use App\Exceptions\AdminBusinessException;
use App\Http\Requests\Admin\Permission\PermissionStoreRequest;
use App\Http\Requests\Admin\Permission\PermissionUpdateRequest;
use App\Http\Resources\Admin\Permission\PermissionCollection;
use App\Http\Resources\Admin\Permission\PermissionResource;
use App\Models\Permission;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;

class PermissionsController extends Controller
{
    /**
     * Create a new PermissionsController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:'.$this->guardName);
        $this->middleware('permission:permissions.index')->only('index');
        $this->middleware('permission:permissions.store')->only('store');
        $this->middleware('permission:permissions.show')->only('show');
        $this->middleware('permission:permissions.update')->only('update');
        $this->middleware('permission:permissions.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\Admin\Permission\PermissionCollection
     */
    public function index(): PermissionCollection
    {
        $query = Permission::where('guard_name', $this->guardName);
        $permissions = is_paginate() ? $query->paginate() : $query->get();

        return new PermissionCollection($permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\Permission\PermissionStoreRequest  $request
     * @return \App\Http\Resources\Admin\Permission\PermissionResource
     * @throws \App\Exceptions\AdminBusinessException
     */
    public function store(PermissionStoreRequest $request): PermissionResource
    {
        try {
            $permission = Permission::create([
                'name' => $request->name,
                'cn_name' => $request->cnName,
                'guard_name' => $this->guardName,
            ]);
            $permission->forgetCachedPermissions();

            return new PermissionResource($permission);
        } catch (PermissionAlreadyExists $e) {
            throw AdminBusinessException::make(AdminCodes::PERMISSION_NAME_ALREADY_EXISTS);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\Admin\Permission\PermissionResource
     */
    public function show(int $id): PermissionResource
    {
        $permission = Permission::findOrFail($id);

        return new PermissionResource($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\Permission\PermissionUpdateRequest  $request
     * @param  int  $id
     * @return \App\Http\Resources\Admin\Permission\PermissionResource
     * @throws \App\Exceptions\AdminBusinessException
     */
    public function update(PermissionUpdateRequest $request, int $id): PermissionResource
    {
        $permission = Permission::findOrFail($id);

        if (Permission::nameExists($request->name, $this->guardName, $permission)) {
            throw AdminBusinessException::make(AdminCodes::PERMISSION_NAME_ALREADY_EXISTS);
        }

        $permission->fill([
            'name' => $request->name,
            'cn_name' => $request->cnName,
        ]);
        $permission->save();
        $permission->forgetCachedPermissions();

        return new PermissionResource($permission);
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
        $permission = Permission::findOrFail($id);
        $permission->delete();

        http_no_content();
    }
}
