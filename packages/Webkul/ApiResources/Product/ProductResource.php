<?php


namespace Webkul\ApiResources\Product;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use ApiPlatform\Laravel\Eloquent\State\Options as EloquentOptions;
use Webkul\Product\Models\Product as ProductModel;

/**
 * Product API Resource
 *
 * This resource is responsible for transforming and formatting product data for API responses.
 * It ensures that product information is presented in a consistent, structured, and user-friendly
 * manner for external clients consuming the API.
 *
 * Features:
 * - Converts internal product models into API-friendly representations.
 * - Formats product attributes, pricing, images, and inventory details.
 * - Supports extensibility for custom product data fields.
 *
 * Usage:
 * Use this resource when you need to expose product data via the API, ensuring that all
 * necessary product details are included and properly formatted for client applications.
 *
 * @package Webkul\ApiResources\Product
 */
#[ApiResource(
	operations: [
		// REST operations
		new GetCollection(
			provider: ProductProviders::class.'::provideProductCollection',
			stateOptions: new EloquentOptions(modelClass: ProductModel::class),
			security: "is_granted('ROLE_USER')"
		),
		new Get(
			provider: ProductProviders::class.'::provideProductItem',
			stateOptions: new EloquentOptions(modelClass: ProductModel::class),
			security: "is_granted('ROLE_USER')"
		),
		new Post(
			provider: ProductProviders::class.'::provideProductItem',
			processor: ProductProviders::class.'::persistProduct',
			stateOptions: new EloquentOptions(modelClass: ProductModel::class),
			security: "is_granted('ROLE_USER')"
		),
		new Put(
			provider: ProductProviders::class.'::provideProductItem',
			processor: ProductProviders::class.'::persistProduct',
			stateOptions: new EloquentOptions(modelClass: ProductModel::class),
			security: "is_granted('ROLE_USER')"
		),
		new Delete(
			provider: ProductProviders::class.'::provideProductItem',
			processor: ProductProviders::class.'::removeProduct',
			stateOptions: new EloquentOptions(modelClass: ProductModel::class),
			security: "is_granted('ROLE_USER')"
		),
	],
	graphQlOperations: [
		// GraphQL Query operations
		new Query(
			provider: ProductProviders::class.'::provideProductItem',
			args: ['id' => ['type' => 'ID!']]
		),
		new QueryCollection(
			provider: ProductProviders::class.'::provideProductCollection',
			paginationEnabled: true,
			paginationType: 'cursor'
		),
		// GraphQL Mutation operations
		new Mutation(
			name: 'create',
			provider: ProductProviders::class.'::provideProductItem',
			processor: ProductProviders::class.'::persistProduct'
		),
		new Mutation(
			name: 'update',
			provider: ProductProviders::class.'::provideProductItem',
			processor: ProductProviders::class.'::persistProduct',
			args: ['id' => ['type' => 'ID!']]
		),
		new Mutation(
			name: 'delete',
			provider: ProductProviders::class.'::provideProductItem',
			processor: ProductProviders::class.'::removeProduct',
			args: ['id' => ['type' => 'ID!']]
		)
	],
	stateOptions: new EloquentOptions(modelClass: ProductModel::class)
)]
class ProductResource
{
	/**
	 * Identifier mapped to the Eloquent model primary key.
	 */
	#[ApiProperty(identifier: true)]
	public int $id;

    #[ApiProperty]
    public ?string $sku = null;

    #[ApiProperty]
    public ?string $type = null;

    #[ApiProperty]
    public ?string $attributeFamilyId = null;

    #[ApiProperty]
    public ?string $name = null;

    #[ApiProperty]
    public ?string $url_key = null;

    #[ApiProperty]
    public ?string $tax_category_id = null;

    #[ApiProperty]
    public ?string $new = null;

    #[ApiProperty]
    public ?string $featured = null;

    #[ApiProperty]
    public ?string $visible_individually = null;

    #[ApiProperty]
    public ?string $status = null;

    #[ApiProperty]
    public ?string $color = null;

    #[ApiProperty]
    public ?string $size = null;

    #[ApiProperty]
    public ?string $price = null;

    #[ApiProperty]
    public ?string $cost = null;

    #[ApiProperty]
    public ?string $special_price = null;

    #[ApiProperty]
    public ?string $special_price_from = null;

    #[ApiProperty]
    public ?string $special_price_to = null;

    #[ApiProperty]
    public ?string $weight = null;

    #[ApiProperty]
    public ?string $length = null;

    #[ApiProperty]
    public ?string $width = null;

    #[ApiProperty]
    public ?string $height = null;

    #[ApiProperty]
    public ?string $meta_title = null;

    #[ApiProperty]
    public ?string $meta_keywords = null;

    #[ApiProperty]
    public ?string $meta_description = null;

    #[ApiProperty]
    public ?string $created_at = null;

    #[ApiProperty]
    public ?string $updated_at = null;
}
