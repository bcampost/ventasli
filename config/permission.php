<?php

return [

    'models' => [

        // 👇 Usamos modelos personalizados para forzar conexión mysql
        'permission' => App\Models\Permission::class,
        'role' => App\Models\Role::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Conexión de base de datos para Spatie
    |--------------------------------------------------------------------------
    |
    | IMPORTANTE: Esto obliga a que roles/permissions usen la BD local (mysql)
    | aunque el usuario esté en otra conexión.
    |
    */
    'database' => [
        'connection' => env('PERMISSION_DB_CONNECTION', 'mysql'),
    ],

    'table_names' => [

        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        'role_pivot_key' => null,
        'permission_pivot_key' => null,
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'events_enabled' => false,
    'teams' => false,
    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];