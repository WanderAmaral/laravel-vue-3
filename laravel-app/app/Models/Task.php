<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'started_at',
        'finished_at',
        'user_id',
    ];

    protected $casts = ['started_at' => 'datetime', 'finished_at' => 'datetime'];
}
