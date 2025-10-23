<?php

namespace Webkul\ApiResources\Models\Category;

class CategoryTranslation extends \Webkul\Category\Models\CategoryTranslation
{
    protected function getFallbackLocale(?string $locale = null): ?string
    {
       return '';
    }
}
