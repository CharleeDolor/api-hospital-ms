<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        "address",
        "birthday",
        "age",
        "gender",
        "marital_status",
        "contact_number" ,
        "blood_type",
        "weight",
        "height"
    ];

    public function records()
    {

        return $this->hasMany(Record::class);
    }

    public function appointments()
    {

        return $this->hasMany(Appointment::class);
    }
}
