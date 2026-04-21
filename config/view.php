<?php

return [
    /*
    |--------------------------------------------------------------------------
    | View Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    /*
    |--------------------------------------------------------------------------
    | Relative Hash
    |--------------------------------------------------------------------------
    |
    | When enabled, compiled view paths are generated using a hash relative
    | to the application base path. This helps avoid collisions when the
    | same project is deployed to multiple locations.
    |
    */

    'relative_hash' => env('VIEW_COMPILED_PATH_HASH', false),
];
