<?php

namespace Huuhadev\LunarApi\Http\Controllers\Actions;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use Huuhadev\LunarApi\Http\Requests\ResourceQuery;

trait FetchMany
{

    /**
     * Fetch zero to many JSON API resources.
     *
     * @param Route $route
     * @param StoreContract $store
     * @return Responsable|Response
     */
    public function index(Route $route, StoreContract $store)
    {
        $request = ResourceQuery::queryMany(
            $resourceType = $route->resourceType()
        );

        $response = null;

        if (method_exists($this, 'searching')) {
            $response = $this->searching($request);
        }

        if ($response) {
            return $response;
        }

        $data = $store
            ->queryAll($resourceType)
            ->withRequest($request)
            ->firstOrPaginate($request->page());

        if (method_exists($this, 'searched')) {
            $response = $this->searched($data, $request);
        }

        return $response ?: DataResponse::make($data)->withQueryParameters($request);
    }
}
