<?php

namespace Huuhadev\LunarApi\Http\Controllers\Actions;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\RelatedResponse;
use LaravelJsonApi\Core\Support\Str;
use Huuhadev\LunarApi\Http\Requests\ResourceQuery;

trait FetchRelated
{

    /**
     * Fetch the related resource(s) for a JSON API relationship.
     *
     * @param Route $route
     * @param StoreContract $store
     * @return Responsable|Response
     */
    public function showRelated(Route $route, StoreContract $store)
    {
        $relation = $route
            ->schema()
            ->relationship($fieldName = $route->fieldName());

        $request = $relation->toOne() ?
            ResourceQuery::queryOne($relation->inverse()) :
            ResourceQuery::queryMany($relation->inverse());

        $model = $route->model();
        $response = null;

        if (method_exists($this, $hook = 'readingRelated' . Str::classify($fieldName))) {
            $response = $this->{$hook}($model, $request);
        }

        if ($response) {
            return $response;
        }

        if ($relation->toOne()) {
            $data = $store->queryToOne(
                $route->resourceType(),
                $model,
                $relation->name()
            )->withRequest($request)->first();
        } else {
            $data = $store->queryToMany(
                $route->resourceType(),
                $model,
                $relation->name()
            )->withRequest($request)->getOrPaginate($request->page());
        }

        if (method_exists($this, $hook = 'readRelated' . Str::classify($fieldName))) {
            $response = $this->{$hook}($model, $data, $request);
        }

        return $response ?: RelatedResponse::make(
            $model,
            $relation->name(),
            $data,
        )->withQueryParameters($request);
    }
}
