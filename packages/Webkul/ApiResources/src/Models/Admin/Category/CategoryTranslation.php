<?php

namespace Webkul\ApiResources\Models\Admin\Category;

class CategoryTranslation extends \Webkul\Category\Models\CategoryTranslation
{
    protected function getFallbackLocale(?string $locale = null): ?string
    {
        return '';
    }
}
