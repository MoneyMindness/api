<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'sum_total' => $this->total_sum,
            'created_at' => (int)$this->created_at->shiftTimezone('utc')->format('U'),
            'updated_at' => $this->updated_at->shiftTimezone('utc')->diffForHumans(),
        ];
    }
}
