<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.reference' => 'required|string',
            'data.attributes.notes' => 'required|string',
            'data.attributes.status' => 'required|string|in:pending,paid,shipped,cancelled',
            'data.relationships.customer.data.id' => 'required|integer',
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use pending, paid, shipped, or cancelled.'
        ];
    }
}
