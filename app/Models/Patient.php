<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'symptoms' => 'array',
    ];

    /**
     * The attributes that should be protected to mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

}
