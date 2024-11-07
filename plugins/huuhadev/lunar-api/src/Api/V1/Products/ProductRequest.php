<?php

namespace Huuhadev\LunarApi\Api\V1\Products;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Validation\Rule as JsonApiRule;
use Huuhadev\LunarApi\Http\Requests\ResourceRequest;

class ProductRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        $post = $this->model();
        $uniqueSlug = Rule::unique('posts', 'slug');

        if ($post) {
            $uniqueSlug->ignoreModel($post);
        }

        return [
            'content' => ['required', 'string'],
            'publishedAt' => ['nullable', JsonApiRule::dateTime()],
            'slug' => ['required', 'string', $uniqueSlug],
            'tags' => JsonApiRule::toMany(),
            'title' => ['required', 'string'],
        ];
    }

}
