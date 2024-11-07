<?php

namespace Huuhadev\LunarApi\Support;

use LaravelJsonApi\Eloquent\Pagination\PagePagination as BasePagination;

class LunarPagination extends BasePagination
{
    public function __construct()
    {
        if ($metaKey = config('lunar-api.pagination.meta_key')) {
            $this->withMetaKey($metaKey);
        }

        $withoutNestedMeta = config('lunar-api.pagination.without_nested_meta', true);
        if ($withoutNestedMeta) {
            $this->withoutNestedMeta();
        }

        $metaCase = config('lunar-api.pagination.meta_case');

        if ($metaCase  == 'dash') {
            $this->withDashCaseMeta();
        } elseif ($metaCase == 'snake') {
            $this->withSnakeCaseMeta();
        }

        if ($defaultPerPageLimit = config('lunar-api.pagination.default_per_page')) {
            $this->withDefaultPerPage($defaultPerPageLimit);
        }

        if ($pageKey = config('lunar-api.pagination.page_key')) {
            $this->withPageKey($pageKey);
        }

        if ($limitKey = config('lunar-api.pagination.limit_key')) {
            $this->withPerPageKey($limitKey);
        }
    }
}
