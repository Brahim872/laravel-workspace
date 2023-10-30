<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Resources\Permission\ListCategoriesPermissionResource;
use App\Http\Resources\Permission\ListPermissionResource;
use App\Http\Resources\Role\ListRoleResource;
use App\Models\CategoryPermission;
use App\Models\Permission;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;


class PermissionController extends Controller
{

    protected $category;
    protected $permission;
    protected $role;

//    /**
//     * MatrixController constructor.
//     *
//     * @param Request $request
//     */
//    public function __construct(Request $request)
//    {
//        $this->category = new CategoryPermission();
//        $this->permission = new Permission();
//        $this->role = new Role();
//
//        parent::__construct($request);
//    }

    /**
     * @return mixed
     */
    public function index()
    {
        try {

            $roles = Role::all();
            $permissions = Permission::all();
            $categories = CategoryPermission::all();

            return returnResponseJson([

                'roles' => new ListRoleResource($roles),
                'permissions' => new ListPermissionResource($permissions),
                'categories' => new ListCategoriesPermissionResource($categories),

            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            returnCatchException($e);
        }
    }

//    /**
//     * @return Response
//     */
    public function postIndex()
    {
//        $services = $this->currentRequest->get('roles', null);
//
//        if ($services) {
//            $allRoles = $this->role->all();
//
//            foreach ($allRoles as $role) {
//                $permissionsSync = [];
//                if (!isset($services[$role->id])) {
//                    continue;
//                }
//                $serviceData = $services[$role->id];
//
//                foreach ($serviceData['permissions'] as $permissionId => $permissionData) {
//                    $permissionsSync[] = $permissionId;
//                }
//                $role->syncPermissions($permissionsSync);
//            }
//        }
//
//        flash()->success(trans('permission.updated'));
//
//        return redirect(route('backend.permission.index'));
    }


}
