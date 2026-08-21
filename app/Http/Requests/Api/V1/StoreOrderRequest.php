<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Support\Facades\Auth;

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
        $isOrdersController = $this->routeIs('orders.store');
        $customerIdAttr = $isOrdersController ? 'data.relationships.customer.data.id' : 'customer';
        $user = Auth::user();
        $customerRule = 'required|integer|exists:users,id';

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.reference' => 'required|string',
            'data.attributes.notes' => 'required|string',
            'data.attributes.status' => 'required|string|in:pending,paid,shipped,cancelled',
        ];

        if ($isOrdersController) {
            $rules['data.relationships'] = 'required|array';
            $rules['data.relationships.customer'] = 'required|array';
            $rules['data.relationships.customer.data'] = 'required|array';
        }

        $rules[$customerIdAttr] = $user
            ? $customerRule . '|size:' . $user->id
            : $customerRule;

        if ($user && $user->tokenCan(Abilities::CreateOrder)) {
            $rules[$customerIdAttr] = $customerRule;
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

    /**
     * Describe the body parameters for the API documentation.
     */
    public function bodyParameters()
    {
        $documentation = [
            'data.attributes.reference' => [
                'description' => "The order's reference",
                'example' => 'No-example'
            ],
            'data.attributes.notes' => [
                'description' => "The order's notes",
                'example' => 'No-example',
            ],
            'data.attributes.status' => [
                'description' => "The order's status",
                'example' => 'No-example',
            ],
        ];

        if ($this->routeIs('orders.store')) {
            $documentation['data.relationships.customer.data.id'] = [
                'description' => 'The customer the order belongs to.',
                'example' => 'No-example'
            ];
        } else {
            $documentation['customer'] = [
                'description' => 'The customer the order belongs to.',
                'example' => 'No-example'
            ];
        }

        return $documentation;
    }
}
