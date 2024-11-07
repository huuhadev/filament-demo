<?php

namespace Huuhadev\LunarApi\Support;

use LaravelJsonApi\Eloquent\Schema;

abstract class BaseSchema extends Schema
{

    public static $model;

    public static function model(): string
    {
        $class = new \ReflectionClass(static::$model);

        if ($class->isInterface()) {
            return app()->get(static::$model)::class;
        }

        return parent::model();
    }

    public function defaultPagination(): ?array
    {
        return [
            'start' => now()->subMonth(),
            'end' => now(),
        ];
    }
}
