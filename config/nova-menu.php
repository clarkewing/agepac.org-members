<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | Optionally override the original Menu resource.
    */

    'resource' => App\Nova\MenuBuilder\MenuResource::class,

    /*
    |--------------------------------------------------------------------------
    | Menus table name
    |--------------------------------------------------------------------------
    */

    'menus_table_name' => 'menus',

    /*
    |--------------------------------------------------------------------------
    | Menu items table name
    |--------------------------------------------------------------------------
    */

    'menu_items_table_name' => 'menu_items',

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Set all the available locales as either [key => name] pairs, a closure
    | or a callable (ie 'locales' => 'nova_lang_get_all_locales').
    */

    'locales' => [
        'fr_FR' => 'Français',
    ],

    /*
    |--------------------------------------------------------------------------
    | Linkable models
    |--------------------------------------------------------------------------
    |
    | Set all the linkable models in an array.
    */

    'linkable_models' => [
        \App\Nova\MenuBuilder\MenuSeparator::class,
        \App\Nova\MenuBuilder\MenuPages::class,
        \App\Nova\MenuBuilder\MenuRoute::class,
    ],
];
