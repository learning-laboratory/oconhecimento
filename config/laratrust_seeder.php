<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadministrador' => [
            'users'      => 'c,r,u,d',
            'articles'   => 'c,r,u,d',
            'categories' => 'c,r,u,d',
            'tag'       => 'c,r,u,d',
            'profile'    => 'r,u',
            'laratrust'  => 'c,r,u,d'
        ],
        'editor' => [
            'articles'   => 'c,r,u,d',
            'categories' => 'c,r,u,d',
            'tags'       => 'c,r,u,d',
            'profile' => 'r,u',
        ],
        'subinscrito' => [
            'profile' => 'r,u',
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
