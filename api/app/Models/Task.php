<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'due_date',
        'status_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'datetime: Y-m-d H:i:s',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tasks', 'task_id', 'user_id')->withPivot('start_time', 'end_time', 'remarks')->withTimestamps();
    }
}
