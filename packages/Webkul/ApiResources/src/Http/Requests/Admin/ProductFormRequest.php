<?php

namespace Webkul\ApiResources\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Core\Rules\Slug;
use Webkul\Product\Helpers\ProductType;

class ProductFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productTypes = implode(',', array_keys(config('product_types', [])));

        return [
            'type'                => 'required|in:' . $productTypes,
            'attribute_family_id' => 'required|exists:attribute_families,id',
            'sku'                 => ['required', 'unique:products,sku', new Slug],
            'super_attributes'    => 'array|min:1',
            'super_attributes.*'  => 'array|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'type.required'                => trans('api-resources.rest-api.admin.catalog.products.error.type-required'),
            'type.in'                      => trans('api-resources.rest-api.admin.catalog.products.error.type-invalid'),
            'attribute_family_id.required' => trans('api-resources.rest-api.admin.catalog.products.error.attribute-family-required'),
            'attribute_family_id.exists'   => trans('api-resources.rest-api.admin.catalog.products.error.attribute-family-exists'),
            'sku.required'                 => trans('api-resources.rest-api.admin.catalog.products.error.sku-required'),
            'sku.unique'                   => trans('api-resources.rest-api.admin.catalog.products.error.sku-unique'),
            'super_attributes.array'       => trans('api-resources.rest-api.admin.catalog.products.error.super-attributes-array'),
            'super_attributes.min'         => trans('api-resources.rest-api.admin.catalog.products.error.super-attributes-min'),
            'super_attributes.*.array'     => trans('api-resources.rest-api.admin.catalog.products.error.super-attributes-array'),
            'super_attributes.*.min'       => trans('api-resources.rest-api.admin.catalog.products.error.super-attributes-min'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'type'                => trans('api-resources.rest-api.admin.catalog.products.type'),
            'attribute_family_id' => trans('api-resources.rest-api.admin.catalog.products.attribute-family'),
            'sku'                 => trans('api-resources.rest-api.admin.catalog.products.sku'),
            'super_attributes'    => trans('api-resources.rest-api.admin.catalog.products.super-attributes'),
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * Performs additional validation for configurable products.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Validate that configurable products have super_attributes
        if (
            ProductType::hasVariants($this->input('type'))
            && !$this->has('super_attributes')
        ) {
            throw new \Illuminate\Validation\ValidationException(
                \Illuminate\Support\Facades\Validator::make(
                    $this->all(),
                    ['super_attributes' => 'required'],
                    ['super_attributes.required' => trans('api-resources.rest-api.admin.catalog.products.error.configurable-error')]
                )
            );
        }

        return $validated;
    }
}
