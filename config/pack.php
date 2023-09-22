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

    'items' => [
        [
            "id" => 1,
            "name" => "free",
            "coust" => 0,
            "discription" =>"discription",
            "descount" =>0,
        ],
        [
            "id" => 2,
            "name" => "pack",
            "coust" =>100,
            "discription" =>"discription",
            "descount" =>0,
        ],
        [
            "id" => 3,
            "name" => "pack_two",
            "coust" =>100,
            "discription" =>"discription",
            "descount" =>0,
        ],
        [
            "id" => 4,
            "name" => "pack_three",
            "coust" =>100,
            "discription" =>"discription",
            "descount" =>0,
        ],
        [
            "id" => 10,
            "name" => "admin",
            "coust" =>100,
            "discription" =>"discription",
            "descount" =>0,
        ],
        [
            "id" => 11,
            "name" => "user",
            "coust" =>100,
            "discription" =>"discription",
            "descount" =>0,
        ],
        [
            "id" => 12,
            "name" => "developer",
            "coust" =>100,
            "discription" =>"discription",
            "descount" =>0,
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
    | Workspace Limit
    |--------------------------------------------------------------------------
    | Number Workspace of each user
    |
     */

    'workspace_limit'=>env('WORKSPACE_LIMIT', 1)

];
