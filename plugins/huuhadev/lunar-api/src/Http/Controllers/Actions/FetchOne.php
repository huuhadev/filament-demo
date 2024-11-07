<?php

namespace Huuhadev\LunarApi\Http\Controllers\Actions;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use Huuhadev\LunarApi\Http\Requests\ResourceQuery;

trait FetchOne
{

    /**
     * Fetch zero to one JSON API resource by id.
     *
     * @param Route $route
     * @param StoreContract $store
     * @return Responsable|Response
     */
    public function show(Route $route, StoreContract $store)
    {
        $request = ResourceQuery::queryOne(
            $resourceType = $route->resourceType()
        );

        $response = null;

        if (method_exists($this, 'reading')) {
            $response = $this->reading($request);
        }

        if ($response) {
            return $response;
        }

        $model = $store
            ->queryOne($resourceType, $route->modelOrResourceId())
            ->withRequest($request)
            ->first();

        if (method_exists($this, 'read')) {
            $response = $this->read($model, $request);
        }

        return $response ?: DataResponse::make($model)->withQueryParameters($request);
    }
}
