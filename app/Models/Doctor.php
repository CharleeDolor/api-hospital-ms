<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "name",
        "email",
        "address",
        "phone_number",
        "medical_license",
        "gender",
        "medical_school_graduated",
        "year_graduated",
        "specialties",
        "career_summary",
        "shift",
    ];

    public function appointments(){

        return $this->hasMany(Appointment::class);

    }

    public function records(){

        return $this->hasMany(Record::class);
    }
}
