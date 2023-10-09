<?php

return [


    /**
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    | we use items in table roles
    | Attention : in table roles we store just id and name, id must be unique also in we store it in table packs.
    | we use table packs as information of the pack
    |
     */

    'roles' => [
        [
            "id" => 1,
            "name" => "admin",
        ],
        [
            "id" => 2,
            "name" => "user",
        ],
        [
            "id" => 3,
            "name" => "developer",
        ],
    ],

    'permissions' => [
        [
            'name' => 'permission',
            'category' => 'permission',
            'access' => [
                "super-backend" => ["read", "create", "delete"],
            ]
        ],
        [
            'name' => 'role',
            'category' => 'permission',
            'access' => [
                "super-backend" => ["read", "create", "delete"],
            ]
        ],
        [
            'name' => 'company',
            'category' => 'company',
            'access' => [
                "super-backend" => ["read", "create", "delete"],
            ]
        ],
        [
            'name' => 'user',
            'category' => 'user',
            'access' => [
                "super-backend" => ["read", "create", "delete"],
                "company" => ["read", "create", "delete"],
            ]
        ],
        [
            'name' => 'contact',
            'category' => 'contact',
            'access' => [
                "super-backend" => ["read", "create", "delete"],
                "company" => ["read", "create", "delete"],
            ]
        ],
        [
            'name' => 'campaign',
            'category' => 'campaign',
            'access' => [
                "super-backend" => ["read", "create", "delete"],
                "company" => ["read", "create", "delete"],
            ]
        ],
    ],




    /**
    |--------------------------------------------------------------------------
    | WorkspaceServices Limit
    |--------------------------------------------------------------------------
    | Number WorkspaceServices of each user
    |
     */

    'workspace_limit'=>env('WORKSPACE_LIMIT', 100)

];
