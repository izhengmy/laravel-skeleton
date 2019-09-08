<?php

namespace App\Http\Controllers\Admin;

use App\Codes\AdminCodes;
use App\Exceptions\AdminBusinessException;
use App\Http\Requests\Admin\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\Admin\AdminUpdateRequest;
use App\Http\Resources\Admin\Admin\AdminCollection;
use App\Http\Resources\Admin\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class AdminsController extends Controller
{
    /**
     * Create a new AdminsController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:'.$this->guardName);
        $this->middleware('permission:admins.index')->only('index');
        $this->middleware('permission:admins.store')->only('store');
        $this->middleware('permission:admins.show')->only('show');
        $this->middleware('permission:admins.update')->only('update');
        $this->middleware('permission:admins.destroy')->only('destroy');
        $this->middleware('permission:admins.restore')->only('restore');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Admin\Admin\AdminCollection
     */
    public function index(Request $request): AdminCollection
    {
        $admins = Admin::withTrashed()->with('roles')->filter($request->all())->orderBy('id', 'DESC')->paginate();

        return new AdminCollection($admins);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\Admin\AdminStoreRequest  $request
     * @return \App\Http\Resources\Admin\Admin\AdminResource
     * @throws \App\Exceptions\AdminBusinessException
     * @throws \Throwable
     */
    public function store(AdminStoreRequest $request): AdminResource
    {
        if (Admin::columnValueExists('username', $request->username, null, Admin::withTrashed())) {
            throw AdminBusinessException::make(AdminCodes::ADMIN_USERNAME_ALREADY_EXISTS);
        }

        if (Admin::columnValueExists('mobile_number', $request->mobileNumber, null, Admin::withTrashed())) {
            throw AdminBusinessException::make(AdminCodes::ADMIN_MOBILE_NUMBER_ALREADY_EXISTS);
        }

        return DB::transaction(function () use ($request) {
            $admin = new Admin([
                'username' => $request->username,
                'mobile_number' => $request->mobileNumber,
                'password' => $request->password,
                'real_name' => $request->realName,
                'enabled' => $request->enabled,
            ]);
            $admin->save();

            try {
                $admin->syncRoles($request->roleIds);

                return new AdminResource($admin);
            } catch (RoleDoesNotExist $e) {
                throw AdminBusinessException::make(AdminCodes::PERMISSION_ROLE_DOES_NOT_EXIST);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\Admin\Admin\AdminResource
     */
    public function show(int $id): AdminResource
    {
        $admin = Admin::with('roles')->findOrFail($id);

        return new AdminResource($admin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\Admin\AdminUpdateRequest  $request
     * @param  int  $id
     * @return \App\Http\Resources\Admin\Admin\AdminResource
     * @throws \App\Exceptions\AdminBusinessException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function update(AdminUpdateRequest $request, int $id): AdminResource
    {
        $admin = Admin::findOrFail($id);

        $this->authorize('admin.update-admin', $admin);

        if (Admin::columnValueExists('username', $request->username, $admin, Admin::withTrashed())) {
            throw AdminBusinessException::make(AdminCodes::ADMIN_USERNAME_ALREADY_EXISTS);
        }

        if (Admin::columnValueExists('mobile_number', $request->mobileNumber, $admin, Admin::withTrashed())) {
            throw AdminBusinessException::make(AdminCodes::ADMIN_MOBILE_NUMBER_ALREADY_EXISTS);
        }

        return DB::transaction(function () use ($admin, $request) {
            $admin->fill([
                'username' => $request->username,
                'mobile_number' => $request->mobileNumber,
                'real_name' => $request->realName,
                'enabled' => $request->enabled,
            ]);
            if ($request->has('password')) {
                $admin->password = $request->password;
            }
            $admin->save();

            try {
                $admin->syncRoles($request->roleIds);

                return new AdminResource($admin);
            } catch (RoleDoesNotExist $e) {
                throw AdminBusinessException::make(AdminCodes::PERMISSION_ROLE_DOES_NOT_EXIST);
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $admin = Admin::findOrFail($id);

        $this->authorize('admin.destroy-admin', $admin);

        $admin->delete();

        http_no_content();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \App\Http\Resources\Admin\Admin\AdminResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore(int $id): AdminResource
    {
        $admin = Admin::withTrashed()->findOrFail($id);

        $this->authorize('admin.restore-admin', $admin);

        $admin->restore();

        return new AdminResource($admin);
    }
}
