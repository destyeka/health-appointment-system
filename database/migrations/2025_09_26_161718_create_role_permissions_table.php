<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id('id_role_permission');

            $table->foreignId('id_role')->constrained(table: 'roles', column: 'id_role')->onDelete('cascade');
            $table->foreignId('id_permission')->constrained(table: 'permissions', column: 'id_permission')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['id_role', 'id_permission']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
