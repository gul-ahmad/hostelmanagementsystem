<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'branch_id' => $this->branch_id,
            'room_number' => $this->room_number,
            'room_floor_number' => $this->room_floor_number,
            'room_status' => $this->room_status,
            'capacity' => $this->capacity,
            'hidden' => $this->hidden,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'featured_image' => ImageResource::make($this->whenLoaded('featuredImage')),
            'prices' => PriceResource::make($this->whenLoaded('prices')),
            'reservations_count' => $this->resource->reservations_count ?? 0,
            'available_slots' => $this->available_slots,

            // $this->merge(Arr::except(parent::toArray($request), [
            //     'user_id', 'created_at', 'updated_at',
            //     'deleted_at'
            // ]))
        ];
    }
}
