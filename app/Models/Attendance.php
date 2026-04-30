<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // This tells Laravel it is allowed to save these fields!
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}