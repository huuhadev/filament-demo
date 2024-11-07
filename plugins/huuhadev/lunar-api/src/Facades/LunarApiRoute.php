<?php

namespace Huuhadev\LunarApi\Facades;

use Illuminate\Support\Facades\Facade;
use Huuhadev\LunarApi\Routing\PendingServerRegistration;
use Huuhadev\LunarApi\Routing\Registrar;

/**
 * Class JsonApiRoute
 *
 * @method static PendingServerRegistration server(string $name)
 */
class LunarApiRoute extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Registrar::class;
    }
}
