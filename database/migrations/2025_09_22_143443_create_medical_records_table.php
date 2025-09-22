<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id('id_record'); 

            // $table->foreignId('id_appointment')->references('id_appointment')->on('appointments')->onDelete('cascade');
            // $table->foreignId('id_prescription')->references('id_prescription')->on('prescription_trackings')->onDelete('cascade'); 
            $table->string('diagnosis');
            $table->string('treatment');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};
