<?php

namespace App\Policies\Admin;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has update admin policy.
     *
     * @param  \App\Models\Admin  $currentAdmin
     * @param  \App\Models\Admin  $admin
     * @return bool
     */
    public function update(Admin $currentAdmin, Admin $admin)
    {
        return $this->base($currentAdmin, $admin);
    }

    /**
     * Determine if the user has destroy admin policy.
     *
     * @param  \App\Models\Admin  $currentAdmin
     * @param  \App\Models\Admin  $admin
     * @return bool
     */
    public function destroy(Admin $currentAdmin, Admin $admin)
    {
        return $this->base($currentAdmin, $admin);
    }

    /**
     * Determine if the user has restore admin policy.
     *
     * @param  \App\Models\Admin  $currentAdmin
     * @param  \App\Models\Admin  $admin
     * @return bool
     */
    public function restore(Admin $currentAdmin, Admin $admin)
    {
        return $this->base($currentAdmin, $admin);
    }

    /**
     * @param  \App\Models\Admin  $currentAdmin
     * @param  \App\Models\Admin  $admin
     * @return bool
     */
    protected function base(Admin $currentAdmin, Admin $admin)
    {
        if ($admin->hasRole('root')) {
            return $currentAdmin->hasRole('root');
        }

        return true;
    }
}
