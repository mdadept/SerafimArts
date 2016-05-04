<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Admin title
    |--------------------------------------------------------------------------
    */
    'title'     => 'Blueprint',

    /*
    |--------------------------------------------------------------------------
    | Admin URI path
    |--------------------------------------------------------------------------
    */
    'path'      => '/admin',

    /*
    |--------------------------------------------------------------------------
    | Authentication configuration
    |--------------------------------------------------------------------------
    |
    | Declared as [ `type` => `db_field_name` ]
    |
    */
    'credinals' => [
        'login'    => 'email',
        'password' => 'password',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication permissions gate
    |--------------------------------------------------------------------------
    */
    'gate' => \Serafim\Blueprint\LaravelServiceProvider::GATE_AUTH_NAME,

    /*
    |--------------------------------------------------------------------------
    | Admin authorization middleware
    |--------------------------------------------------------------------------
    */
    'middleware' => \Serafim\Blueprint\LaravelServiceProvider::MIDDLEWARE_AUTH_NAME,

    /*
    |--------------------------------------------------------------------------
    | Blueprints config
    |--------------------------------------------------------------------------
    |
    | `reader` - Blueprint information reader
    |
    | `driver` - Blueprint entity reader driver
    |
    | `items` - List of declared blueprints as [ `blueprint_class` => `entity_class` ]
    |
    */
    'blueprints' => [

        'reader' => \Serafim\Blueprint\Repositories\BlueprintsMetadataRepository::class,

        'driver' => \Serafim\Blueprint\Repositories\EloquentEntityRepository::class,

        'items' => [
            Blueprints\User::class => \Domains\User\User::class
        ]
    ],
];