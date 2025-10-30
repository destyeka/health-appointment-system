<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, make sure the queue_number column exists and is an integer
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('queue_number')->nullable(false)->default(0)->change();
        });

        // Update existing appointments with sequential queue numbers
        $appointments = DB::table('appointments')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get()
            ->groupBy(function($app) {
                return $app->id_doctor . '_' . $app->appointment_date;
            });

        foreach ($appointments as $group) {
            $queueNumber = 1;
            foreach ($group as $appointment) {
                DB::table('appointments')
                    ->where('id_appointment', $appointment->id_appointment)
                    ->update(['queue_number' => $queueNumber]);
                $queueNumber++;
            }
        }
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('queue_number')->nullable()->change();
        });
    }
};