<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'document' => [
                'required',
                'string',
                'unique:clients,document,' . $this->client,
            ],
            'email' => 'required|email|unique:clients,email,' . $this->client,
            'status' => 'required|in:active,inactive'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $doc = preg_replace('/\D/', '', $this->document);
            if (!$this->isValidCpfCnpj($doc)) {
                $validator->errors()->add('document', 'CPF ou CNPJ inválido.');
            }
        });
    }

    private function isValidCpfCnpj($doc)
    {
        if (strlen($doc) === 11) {
            return $this->validateCpf($doc);
        }
        if (strlen($doc) === 14) {
            return $this->validateCnpj($doc);
        }
        return false;
    }

    private function validateCpf($cpf)
    {
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
    private function validateCnpj($cnpj)
    {
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        $length = strlen($cnpj) - 2;
        $numbers = substr($cnpj, 0, $length);
        $digits = substr($cnpj, $length);
        $sum = 0;
        $pos = $length - 7;
        for ($i = $length; $i >= 1; $i--) {
            $sum += $numbers[$length - $i] * $pos--;
            if ($pos < 2) {
                $pos = 9;
            }
        }
        $result = $sum % 11 < 2 ? 0 : 11 - ($sum % 11);
        if ($result != $digits[0]) {
            return false;
        }
        $length = $length + 1;
        $numbers = substr($cnpj, 0, $length);
        $sum = 0;
        $pos = $length - 7;
        for ($i = $length; $i >= 1; $i--) {
            $sum += $numbers[$length - $i] * $pos--;
            if ($pos < 2) {
                $pos = 9;
            }
        }
        $result = $sum % 11 < 2 ? 0 : 11 - ($sum % 11);
        return $result == $digits[1];
    }
}
