<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'created_at' => (int)$this->created_at->shiftTimezone('utc')->format('U'),
            'updated_at' => $this->updated_at->shiftTimezone('utc')->diffForHumans(),
        ];
    }
}
