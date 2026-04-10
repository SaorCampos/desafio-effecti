<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unitValue' => 'required|numeric|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'items.required' => 'O contrato precisa ter pelo menos um item de serviço.',
            'items.*.service_id.exists' => 'Um dos serviços selecionados é inválido.',
            'items.*.quantity.min' => 'A quantidade mínima de um item é 1.',
            'end_date.after_or_equal' => 'A data de término não pode ser anterior à data de início.',
            'items.*.unitValue.min' => 'O valor unitário deve ser um número positivo.',
            'date' => 'O campo :attribute deve ser uma data válida.',
            'client_id.exists' => 'O cliente selecionado é inválido.',
            'required' => 'O campo :attribute é obrigatório.',
        ];
    }
}
