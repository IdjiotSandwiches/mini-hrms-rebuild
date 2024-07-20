<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'user_id',
        'day',
        'start_time',
        'end_time',
        'work_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
