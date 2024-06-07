<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable= [
        "type",
        "queue_number",
        "day",
        "patient_id",
        "doctor_id"
    ];
}
