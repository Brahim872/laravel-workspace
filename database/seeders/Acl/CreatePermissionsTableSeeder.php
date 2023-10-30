<?php

namespace Database\Seeders\Acl;

use App\Models\Permission;
use DB;

class CreatePermissionsTableSeeder extends AbstractPermissionsRolesSeeder
{
    /**
     * Element position
     * @var int
     */
    protected $position = 0;

    /**
     * Permissions array
     *
     * @var array
     */
    protected $datas = [
        [
            'name' => 'backend.dashboard',
            'display_name' => 'app.Dashboard',
            'category_id' => 1,
            'access' => [
                'read' => ['Develop', 'superadmin', 'admin', 'Account'],
            ],
        ],

        [
            'name' => 'backend.user',
            'display_name' => 'user.users',
            'category_id' => 2,
            'access' => [
                'create' => ['Develop', 'superadmin', 'Account'],
                'read' => ['Develop', 'superadmin', 'Account'],
                'update' => ['Develop', 'superadmin', 'admin', 'Account'],
                'delete' => ['Develop', 'superadmin', 'Account'],
            ],
        ],

        [
            'name' => 'backend.role',
            'display_name' => 'role.roles',
            'category_id' => 3,
            'access' => [
                'create' => ['Develop', 'superadmin'],
                'read' => ['Develop', 'superadmin'],
                'update' => ['Develop', 'superadmin'],
                'delete' => ['Develop', 'superadmin'],
            ],
        ],

        [
            'name' => 'backend.permission',
            'display_name' => 'permission.permissions',
            'category_id' => 4,
            'access' => [
                'read' => ['Develop', 'superadmin'],
                'update' => ['Develop', 'superadmin'],
            ],
        ],

    ];

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($this->datas as $data) {
            if (!empty($data['access'])) {
                $this->createAccessPermissions($data);
            } else {
                $this->addPermission($data);
            }
        }
    }

    /**
     * Create all access permission in access field, example: create,read,update,delete
     *
     * @param array $data
     */
    protected function createAccessPermissions(array $data)
    {
        foreach ($data['access'] as $key => $access) {
            $permission_access = !is_array($access) ? $access : $key;
            $info = $data;
            if (isset($info['access'][$permission_access])) {
                $info['roles'] = $info['access'][$permission_access];
            }
            unset($info['access']);
            $info['name'] = $info['name'] . '.' . $permission_access;

            $info['display_name'] = trans($info['display_name']) . ' / ' . trans('access_permissions.' . $permission_access);
            $this->addPermission($info);
        }
    }

    /**
     * Create permission
     *
     * @param array $info
     */
    protected function addPermission(array $info)
    {
        $roles = isset($info['roles']) ? $info['roles'] : null;
        if ($roles == '*') {
            $roles = $this->ALL_ROLES;
        }
        unset($info['roles']);
        $permission = Permission::where("name", "=", $info['name'])->first();
        $info['position'] = ++$this->position;
        $info['guard_name'] = "sanctum";
        if (!$permission) {
            $permission = new Permission;
            $permission->fill($info);
            $permission->save();
        } else {
            $permission->update($info);
        }
        if ($roles) {
            $this->attachPermissionToRoles($info['name'], $roles);
        }
    }
}
