<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archived_records', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_consultation');
            $table->string('patient_name');
            $table->string('patient_contact_number');
            $table->string('patient_email');
            $table->string('diagnosis');
            $table->string('recommendations');
            $table->string('doctor_name');
            $table->string('doctor_contact_number');
            $table->string('doctor_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivedrecords');
    }
};
