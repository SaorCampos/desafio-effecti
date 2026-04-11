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
            'contract_id' => 'integer|nullable',
            'client_name' => 'string|nullable',
            'service_name' => 'string|nullable',
            'start_date' => 'date|format:Y-m-d|nullable',
            'end_date' => 'date|after_or_equal:start_date|format:Y-m-d|nullable',
        ];
    }
}
