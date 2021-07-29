<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that should be protected to mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the patient that has an appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
