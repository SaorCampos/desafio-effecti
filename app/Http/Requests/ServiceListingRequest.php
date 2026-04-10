<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'string',
            'min_base_value' => 'numeric|min:0',
            'max_base_value' => 'numeric|min:0|gte:min_base_value',
        ];
    }
}
