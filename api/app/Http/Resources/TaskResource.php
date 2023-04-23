<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'due_date' => (new Carbon($this->due_date))->format('d-m-Y'),
            'due_time' => (new Carbon($this->due_date))->format('g:i:s a'),
            'status_id' => $this->status_id,
            'selected_users' => $this->users->pluck('id'),
            'start_date' => (new Carbon($this->users[0]->pivot->start_time))->format('d-m-Y'),
            'start_time' => (new Carbon($this->users[0]->pivot->start_time))->format('g:i:s a'),
            'end_date' => (new Carbon($this->users[0]->pivot->end_time))->format('d-m-Y'),
            'end_time' => (new Carbon($this->users[0]->pivot->end_time))->format('g:i:s a'),
            'remarks' => $this->users[0]->pivot->remarks,
            'created_at' => $this->created_at->format('d/m/Y H:i:s a'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i:s a'),
        ];
    }
}
