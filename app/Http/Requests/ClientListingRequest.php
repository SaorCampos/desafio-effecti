<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'string',
            'email' => 'string|email',
            'document' => 'string|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$|^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/',
        ];
    }
}
