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
        $customerIdAttr = $this->routeIs('orders.store') ? 'data.relationships.customer.data.id' : 'customer';

        $rules = [
            'data.attributes.reference' => 'required|string',
            'data.attributes.notes' => 'required|string',
            'data.attributes.status' => 'required|string|in:pending,paid,shipped,cancelled',
            $customerIdAttr => 'required|integer|exists:users,id'
        ];

        $user = $this->user();

        if ($user->tokenCan(Abilities::CreateOwnOrder)) {
            $rules[$customerIdAttr] .= '|size:' . $user->id;
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->routeIs('customers.orders.store')) {
            $this->merge([
                'customer' => $this->route('customer')
            ]);
        }
    }
}
