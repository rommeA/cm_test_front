<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


#[OA\Schema(
    description: 'User resource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/User'
        ),
    ]
)]
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
