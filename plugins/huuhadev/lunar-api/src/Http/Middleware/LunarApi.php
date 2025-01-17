<?php

namespace Huuhadev\LunarApi\Http\Middleware;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LaravelJsonApi\Contracts\Routing\Route as RouteContract;
use LaravelJsonApi\Contracts\Server\Repository;
use LaravelJsonApi\Contracts\Server\Server;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use Huuhadev\LunarApi\Routing\Route;

class LunarApi
{

    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var Repository
     */
    private Repository $servers;

    /**
     * BootJsonApi constructor.
     *
     * @param Container $container
     * @param Repository $servers
     */
    public function __construct(Container $container, Repository $servers)
    {
        $this->container = $container;
        $this->servers = $servers;
    }

    /**
     * Handle the request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $name
     * @return mixed
     */
    public function handle($request, Closure $next, string $name)
    {
        $this->container->instance(
            Server::class,
            $server = $this->servers->server($name)
        );

        $this->container->instance(
            RouteContract::class,
            $route = new Route($this->container, $server, $request->route())
        );


        if (method_exists($server, 'serving')) {
            $this->container->call([$server, 'serving']);
        }

        /**
         * Once the server is set up, we can substitute bindings. This must
         * happen after the `serving` hook, in case that hook has added any
         * Eloquent scopes.
         */
        $route->substituteBindings();

        /**
         * We will also override the Laravel page resolver, as we know this is
         * a JSON:API request, and the specification would have the page number
         * nested under the `page` query parameter.
         */
        PagePagination::bindPageResolver();

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent.
     *
     * @return void
     */
    public function terminate(): void
    {
        $this->container->forgetInstance(Server::class);
        $this->container->forgetInstance(RouteContract::class);
    }
}
