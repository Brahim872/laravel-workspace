<?php

namespace Database\Seeders\Acl;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

abstract class AbstractPermissionsRolesSeeder extends Seeder
{
    protected  $ALL_ROLES = ['superadmin', 'admin', 'user'];

    /**
     * Attach the permission to all roles given
     * @param String $permissionName
     * @param Array $roles
     */
    protected function attachPermissionToRoles($permissionName, $roles)
    {
        $permission = Permission::where("name", "=", $permissionName)->first();
        if ($permission) {
            foreach ($roles as $k => $roleName) {
                $role = Role::where("name", "=", $roleName)->first();
                if ($role && !$role->hasPermissionTo($permissionName)) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }

    /**
     * Attach the permission to all roles given
     * @param String $permissionName
     * @param String $roleName
     */
    protected function attachPermissionToRole($permissionName, $roleName)
    {
        $permission = Permission::where("name", "=", $permissionName)->first();
        if ($permission) {
            $role = Role::where("name", "=", $roleName)->first();
            if ($role && !$role->hasPermissionTo($permissionName)) {
                $role->givePermissionTo($permission);
            }
        }
    }

    /**
     * Detach the permission to all roles given
     * @param String $permissionName
     * @param Array $roles
     */
    protected function detachPermissionToRoles($permissionName, $roles)
    {
        $permission = Permission::where("name", "=", $permissionName)->first();
        if ($permission) {
            foreach ($roles as $k => $roleName) {
                $role = Role::where("name", "=", $roleName)->first();
                if ($role && $role->hasPermissionTo($permissionName)) {
                    $role->detachPermission($permission);
                }
            }
        }
    }

    /**
     * Detach the permission to all roles given
     * @param String $permissionName
     * @param String $roleName
     */
    protected function detachPermissionToRole($permissionName, $roleName)
    {
        $permission = Permission::where("name", "=", $permissionName)->first();
        if ($permission) {
            $role = Role::where("name", "=", $roleName)->first();
            if ($role && $role->hasPermissionTo($permissionName)) {
                $role->detachPermission($permission);
            }
        }
    }
}
