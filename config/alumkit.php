<?php

declare(strict_types=1);

return [

    'auth' => [

        'user_model' => 'App\\Models\\User',

    ],

    'seeder' => [
        'admin_email' => env('ALUMKIT_ADMIN_EMAIL', 'admin@example.com'),
        'admin_password' => env('ALUMKIT_ADMIN_PASSWORD'),
    ],

    'roles' => [
        'admin' => 'admin',
        'moderator' => 'moderator',
        'approved' => 'approved',
        'pending' => 'pending',
        'rejected' => 'rejected',
        'suspended' => 'suspended',
    ],

];
