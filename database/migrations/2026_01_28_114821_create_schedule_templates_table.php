<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('schedule_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: "Turno Mañana 8-5"
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('lunch_duration_minutes')->default(45);
            $table->integer('break_duration_minutes')->default(15);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('schedule_templates');
    }
};
