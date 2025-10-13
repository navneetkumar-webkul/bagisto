<?php

declare(strict_types=1);

namespace Webkul\ApiResources\Support;

use ApiPlatform\State\Pagination\PaginatorInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LaravelPaginator;

/**
 * Adapts Laravel's paginator to API Platform's PaginatorInterface.
 */
class EloquentPaginator implements PaginatorInterface, \IteratorAggregate
{
    private LaravelPaginator $paginator;

    public function __construct(LaravelPaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function count(): int
    {
        return count($this->paginator->items());
    }

    public function getLastPage(): float
    {
        return (float) $this->paginator->lastPage();
    }

    public function getTotalItems(): float
    {
        return (float) $this->paginator->total();
    }

    public function getCurrentPage(): float
    {
        return (float) $this->paginator->currentPage();
    }

    public function getItemsPerPage(): float
    {
        return (float) $this->paginator->perPage();
    }

    public function getIterator(): \Traversable
    {
        $items = array_map(function ($item) {
            return ProductDataMapper::mapToResource($item, \Webkul\ApiResources\Product\ProductResource::class);
        }, $this->paginator->items());

        return new \ArrayIterator($items);
    }
}
