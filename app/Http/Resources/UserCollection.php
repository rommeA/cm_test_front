<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

#[OA\Schema(
    description: 'User resource collection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/User')
        ),
        new OA\Property(
            property: 'links',
            ref: '#/components/schemas/ResourceLinks'
        ),
        new OA\Property(
            property: 'meta',
            ref: '#/components/schemas/ResourceMeta'
        ),
    ]
)]
class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
