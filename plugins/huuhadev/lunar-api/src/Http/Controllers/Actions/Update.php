<?php

namespace Huuhadev\LunarApi\Http\Controllers\Actions;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use Huuhadev\LunarApi\Http\Requests\ResourceQuery;
use Huuhadev\LunarApi\Http\Requests\ResourceRequest;

trait Update
{

    /**
     * Update an existing resource.
     *
     * @param Route $route
     * @param StoreContract $store
     * @return Responsable|Response
     */
    public function update(Route $route, StoreContract $store)
    {
        $request = ResourceRequest::forResource(
            $resourceType = $route->resourceType()
        );

        $query = ResourceQuery::queryOne($resourceType);

        $model = $route->model();
        $response = null;

        if (method_exists($this, 'saving')) {
            $response = $this->saving($model, $request, $query);
        }

        if (!$response && method_exists($this, 'updating')) {
            $response = $this->updating($model, $request, $query);
        }

        if ($response) {
            return $response;
        }

        $model = $store
            ->update($resourceType, $model)
            ->withRequest($query)
            ->store($request->validated());

        if (method_exists($this, 'updated')) {
            $response = $this->updated($model, $request, $query);
        }

        if (!$response && method_exists($this, 'saved')) {
            $response = $this->saved($model, $request, $query);
        }

        return $response ?: DataResponse::make($model)->withQueryParameters($query);
    }
}
