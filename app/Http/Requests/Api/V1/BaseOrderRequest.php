<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseOrderRequest extends FormRequest
{
    /**
     * Map the request attributes to their database columns.
     */
    public function mappedAttributes()
    {
        $attributeMap = [
            'data.attributes.reference' => 'reference',
            'data.attributes.notes' => 'notes',
            'data.attributes.status' => 'status',
            'data.attributes.createdAt' => 'created_at',
            'data.attributes.updatedAt' => 'updated_at',
            'data.relationships.customer.data.id' => 'user_id',
        ];

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
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
