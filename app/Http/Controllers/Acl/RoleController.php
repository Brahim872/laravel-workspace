<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Resources\Role\ListRoleResource;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{

    public function index()
    {
        $role = Role::all()->sortByDesc('created_at');
        return returnResponseJson(['roles' => new ListRoleResource($role)], Response::HTTP_OK);
    }

}
