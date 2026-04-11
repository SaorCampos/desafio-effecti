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
            'name' => 'string|nullable',
            'email' => 'string|nullable',
            'document' => 'string|nullable',
            'client_id' => 'integer|exists:clients,id|nullable',
            'status' => 'in:active,inactive|nullable',
        ];
    }
}
