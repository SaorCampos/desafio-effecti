<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'base_value' => 'required|numeric|min:0',
            'service_id' => 'sometimes|integer|exists:services,id'
        ];
    }
}
