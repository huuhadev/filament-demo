<?php

namespace Huuhadev\LunarApi\Http\Controllers\Actions;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Support\Str;
use Huuhadev\LunarApi\Http\Requests\ResourceQuery;
use Huuhadev\LunarApi\Http\Requests\ResourceRequest;
use LogicException;

trait AttachRelationship
{

    /**
     * Attach records to a to-many relationship.
     *
     * @param Route $route
     * @param StoreContract $store
     * @return Response|Responsable
     */
    public function attachRelationship(Route $route, StoreContract $store)
    {
        $relation = $route
            ->schema()
            ->relationship($fieldName = $route->fieldName());

        if (!$relation->toMany()) {
            throw new LogicException('Expecting a to-many relation for an attach action.');
        }

        $request = ResourceRequest::forResource(
            $resourceType = $route->resourceType()
        );

        $query = ResourceQuery::queryMany($relation->inverse());

        $model = $route->model();
        $response = null;

        if (method_exists($this, $hook = 'attaching' . Str::classify($fieldName))) {
            $response = $this->{$hook}($model, $request, $query);
        }

        if ($response) {
            return $response;
        }

        $result = $store
            ->modifyToMany($resourceType, $model, $fieldName)
            ->withRequest($query)
            ->attach($request->validatedForRelation());

        if (method_exists($this, $hook = 'attached' . Str::classify($fieldName))) {
            $response = $this->{$hook}($model, $result, $request, $query);
        }

        return $response ?: response('', Response::HTTP_NO_CONTENT);
    }
}
