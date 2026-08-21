<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    // public static $wrap = 'order';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'order',
            'id' => $this->id,
            'attributes' => [
                'reference' => $this->reference,
                'notes' => $this->when(
                    $request->routeIs('orders.show'),
                    $this->notes
                ),
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ],
            'relationships' => [
                'customer' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id
                    ],
                    'links' => [
                        'self' => route('customers.show', ['customer' => $this->user_id])
                    ]
                ]
            ],
            'includes' => new UserResource($this->whenLoaded('customer')),
            'links' => [
                'self' => route('orders.show', ['order' => $this->id])
            ]
        ];
    }
}
