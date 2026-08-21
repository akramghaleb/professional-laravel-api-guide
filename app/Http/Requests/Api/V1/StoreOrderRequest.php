<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class StoreOrderRequest extends BaseOrderRequest
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
            'data.relationships.customer.data.id' => 'required|integer|exists:users,id'
        ];

        $user = $this->user();

        if ($this->routeIs('orders.store')) {
            if ($user->tokenCan(Abilities::CreateOwnOrder)) {
                $rules['data.relationships.customer.data.id'] .= '|size:' . $user->id;
            }
        }

        return $rules;
    }
}
