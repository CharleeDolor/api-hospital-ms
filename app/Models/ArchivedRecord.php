<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date_of_consultation',
        'patient_name',
        'patient_contact_number',
        'patient_email',
        'diagnosis',
        'doctor_name',
        'doctor_contact_number',
        'doctor_email'
    ];
}
