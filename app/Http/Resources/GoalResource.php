<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'goalID' => $this->goalID,
            'title' => $this->title,
            'description' => $this->description,
            'semester' => $this->semester,
            'deadline' => $this->deadline->format('Y-m-d'),
            'status' => $this->status,
        ];
    }
}