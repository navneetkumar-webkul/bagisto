<?php

namespace Webkul\ApiResources\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChannelFormRequest extends FormRequest
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
        return [
            'code'                => 'required|string|max:255|unique:channels,code',
            'name'                => 'required|string|max:255',
            'default_locale_id'   => 'required|integer|exists:locales,id',
            'locales'             => 'required|array|min:1',
            'locales.*'           => 'integer|exists:locales,id',
            'inventory_sources'   => 'required|array|min:1',
            'inventory_sources.*' => 'integer|exists:inventory_sources,id',
            'root_category_id'    => 'required|integer|exists:categories,id',
            'currencies'          => 'required|array|min:1',
            'currencies.*'        => 'integer|exists:currencies,id',
            'base_currency_id'    => 'required|integer|exists:currencies,id',
            'meta_title'          => 'required|string|max:255',
            'meta_keywords'       => 'required|string|max:255',
            'meta_description'    => 'required|string|max:1000',
            'description'         => 'nullable|string|max:1000',
            'hostname'            => 'nullable|string|max:255|unique:channels,hostname',
            'theme'               => 'nullable|string|max:255',
            'logo'                => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'favicon'             => 'nullable|file|mimes:ico,png|max:512',
            'settings'            => 'nullable|array',
            'settings.message'    => 'nullable|string|max:1000',
            'allowed_ips'         => 'nullable|string|max:1000',
            'status'              => 'nullable|boolean',
            'is_maintenance_on'   => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validation errors.
     * Uses localized messages from lang files.
     */
    public function messages(): array
    {
        return [
            'code.required'              => trans('api-resources.validations.channel.code_required'),
            'code.unique'                => trans('api-resources.validations.channel.code_unique'),
            'name.required'              => trans('api-resources.validations.channel.name_required'),
            'default_locale_id.required' => trans('api-resources.validations.channel.default_locale_id_required'),
            'default_locale_id.exists'   => trans('api-resources.validations.channel.default_locale_id_exists'),
            'locales.required'           => trans('api-resources.validations.channel.locales_required'),
            'locales.*.exists'           => trans('api-resources.validations.channel.locales_exists'),
            'inventory_sources.required' => trans('api-resources.validations.channel.inventory_sources_required'),
            'inventory_sources.*.exists' => trans('api-resources.validations.channel.inventory_sources_exists'),
            'root_category_id.required'  => trans('api-resources.validations.channel.root_category_id_required'),
            'root_category_id.exists'    => trans('api-resources.validations.channel.root_category_id_exists'),
            'currencies.required'        => trans('api-resources.validations.channel.currencies_required'),
            'currencies.*.exists'        => trans('api-resources.validations.channel.currencies_exists'),
            'base_currency_id.required'  => trans('api-resources.validations.channel.base_currency_id_required'),
            'base_currency_id.exists'    => trans('api-resources.validations.channel.base_currency_id_exists'),
            'meta_title.required'        => trans('api-resources.validations.channel.meta_title_required'),
            'meta_keywords.required'     => trans('api-resources.validations.channel.meta_keywords_required'),
            'meta_description.required'  => trans('api-resources.validations.channel.meta_description_required'),
            'hostname.unique'            => trans('api-resources.validations.channel.hostname_unique'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status'            => $this->toBoolean($this->input('status')),
            'is_maintenance_on' => $this->toBoolean($this->input('is_maintenance_on')),
        ]);
    }

    /**
     * Convert string boolean to actual boolean.
     */
    private function toBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower((string) $value), ['true', '1', 'on', 'yes']);
    }
}
