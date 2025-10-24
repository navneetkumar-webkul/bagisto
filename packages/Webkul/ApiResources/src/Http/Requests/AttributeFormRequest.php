<?php

namespace Webkul\ApiResources\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeFormRequest extends FormRequest
{
    /**
      * Get the validation rules that apply to the request.
      *
      * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
      */
     public function rules(): array
     {
         return [
            'code'        => 'required|unique:attributes|max:2',
         ];
     }
}
