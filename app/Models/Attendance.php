<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
