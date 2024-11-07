<?php

namespace Huuhadev\LunarApi\Api\V1\Products;

use App\Models\Shop\Product;
use Huuhadev\LunarApi\Support\BaseSchema;
use LaravelJsonApi\Eloquent\Fields\Number;
use Huuhadev\LunarApi\Support\LunarPagination;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;

class ProductSchema extends BaseSchema
{

    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static $model = Product::class;

    protected bool $selfLink = false;

    /**
     * @inheritDoc
     */
    public function authorizable(): bool
    {
        return false;
    }

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('name'),
            Str::make('sku'),
            Str::make('description'),
            Str::make('description'),
            Number::make('qty'),
            Number::make('security_stock'),
            Str::make('old_price'),
            Str::make('price'),
            Str::make('cost'),
            Str::make('type'),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
        ];
    }

    /**
     * Get the resource filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
        ];
    }

    /**
     * Get the resource paginator.
     *
     * @return Paginator|null
     */
    public function pagination(): ?Paginator
    {
        return LunarPagination::make();
    }
}
