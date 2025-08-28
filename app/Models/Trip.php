<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_name',
        'start_date',
        'end_date',
        'description',
        'sites'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sites' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
