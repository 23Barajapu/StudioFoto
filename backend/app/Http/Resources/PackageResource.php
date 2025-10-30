<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'formatted_price' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'duration_hours' => $this->duration_hours,
            'photo_count' => $this->photo_count,
            'edited_photo_count' => $this->edited_photo_count,
            'include_makeup' => $this->include_makeup,
            'include_outfit' => $this->include_outfit,
            'features' => $this->features,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
