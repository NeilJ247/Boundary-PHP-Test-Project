<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'db' => [
            'driver' => 'pgsql',
            'host' => 'db',
            'port' => '5432',
            'database' => 'postgres',
            'username' => 'postgres',
            'password' => 'postgres',
            'schema' => 'public',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]
    ],
];
