<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->pivot->user_id,
            'task_id' => $this->pivot->task_id,
            'start_date' => (new Carbon($this->pivot->start_date))->format('d-m-Y'),
            'start_time' => (new Carbon($this->pivot->start_date))->format('g:i:s a'),
            'end_date' => (new Carbon($this->pivot->end_date))->format('d-m-Y'),
            'end_time' => (new Carbon($this->pivot->end_date))->format('g:i:s a'),
            'remarks' => $this->pivot->remarks,
            'created_at' => $this->pivot->created_at->format('d/m/Y H:i:s a'),
            'updated_at' => $this->pivot->updated_at->format('d/m/Y H:i:s a'),
        ];
    }
}
