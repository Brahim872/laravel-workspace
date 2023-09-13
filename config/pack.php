<?php

return [


    /**
     * we use items in table roles
     * Attention : in table roles we store just id and name, id must be unique
     *             also in we store it in table packs.
     *
     * we use table packs as information of the pack
     *
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


];
