<?php

use Huuhadev\LunarApi\Facades\LunarApiRoute;
use Huuhadev\LunarApi\Routing\Relationships;
use Huuhadev\LunarApi\Routing\ResourceRegistrar;
use Huuhadev\LunarApi\Http\Controllers\ApiController;

LunarApiRoute::server('v1')
    ->prefix('api/v1')
    ->domain('cybersilk.com')
    ->resources(function (ResourceRegistrar $server) {

        $server->resource('users', ApiController::class)->readOnly();
        $server->resource('products', ApiController::class)->readOnly();

        // $server->resource('posts', ApiController::class)
        //     ->relationships(function (Relationships $relations) {
        //         $relations->hasOne('author')->readOnly();
        //         $relations->hasMany('comments')->readOnly();
        //         $relations->hasMany('tags');
        //     });
    });
