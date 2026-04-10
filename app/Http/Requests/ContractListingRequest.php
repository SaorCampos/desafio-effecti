<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'client_name' => 'string',
            'service_name' => 'string',
            'start_date' => 'date|format:Y-m-d',
            'end_date' => 'date|after_or_equal:start_date|format:Y-m-d',
        ];
    }
}
