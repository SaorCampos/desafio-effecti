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
            'name' => 'string|nullable',
            'min_base_value' => 'numeric|min:0|nullable',
            'max_base_value' => 'numeric|min:0|nullable',
            'service_id' => 'integer|exists:services,id|nullable',
        ];
    }
}
