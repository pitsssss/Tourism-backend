<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
        [
            "name" => $this->name,
            "transport" => $this->transport,
            "description" => $this->description,
            "image" => $this->image,
            "hotel_id" => $this->hotel_id,
            "category_id" => $this->category_id,
            "price" => $this->price,


        ];
    }
}
