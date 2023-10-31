<?php

return [
    /**
     * |--------------------------------------------------------------------------
     * | WorkspaceServices Limit
     * |--------------------------------------------------------------------------
     * | Number WorkspaceServices of each user
     * |
     */

    'workspace_limit' => env('WORKSPACE_LIMIT', 100),


    /**
     * |--------------------------------------------------------------------------
     * | Fields of the features
     * |--------------------------------------------------------------------------
     * | TODO:features config
     * |
     */

    'plan_features' => [
        [
            'key' => 'number_apps_building',
            'value' => '0',
            'type' => 'number'
        ],
        [
            'key' => 'number_chart_app',
            'value' => '10',
            'type' => 'number'
        ],
        [
            'key' => 'app_store_display',
            'value' => 'off',
            'type' => 'boolean'
        ],
        [
            'key' => 'play_store_display',
            'value' => 'on',
            'type' => 'boolean'
        ],
        'filter' =>
            [
                [
                    'key' => 'MinInstalls',
                    'value' => 'on',
                    'type' => 'boolean'
                ],
                [
                    'key' => 'MaxInstalls',
                    'value' => 'on',
                    'type' => 'boolean'
                ],
                [
                    'key' => 'MaxInstalls',
                    'value' => 'on',
                    'type' => 'boolean'
                ],
                [
                    'key' => 'MinRatings',
                    'value' => 'on',
                    'type' => 'boolean'
                ],
                [
                    'key' => 'MaxRatings',
                    'value' => 'on',
                    'type' => 'boolean'
                ],
            ],
    ],


];
