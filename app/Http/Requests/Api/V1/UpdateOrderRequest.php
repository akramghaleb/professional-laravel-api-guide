<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class UpdateOrderRequest extends BaseOrderRequest
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
            'data.attributes.reference' => 'sometimes|string',
            'data.attributes.notes' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:pending,paid,shipped,cancelled',
            'data.relationships.customer.data.id' => 'sometimes|integer',
        ];

        if ($this->user()->tokenCan(Abilities::UpdateOwnOrder)) {
            $rules['data.relationships.customer.data.id'] = 'prohibited';
        }

        return $rules;
    }
}
