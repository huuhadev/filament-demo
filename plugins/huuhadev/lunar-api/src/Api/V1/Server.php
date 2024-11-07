<?php

namespace Huuhadev\LunarApi\Api\V1;

use LaravelJsonApi\Core\Document\JsonApi;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{

    /**
     * The base URI namespace for this server.
     *
     * @var string
     */
    protected string $baseUri = '/api/v1';

    // public function jsonApi(): JsonApi
    // {
    //     return JsonApi::make('1.0');
    // }

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            Products\ProductSchema::class,
            Users\UserSchema::class,
        ];
    }
}
