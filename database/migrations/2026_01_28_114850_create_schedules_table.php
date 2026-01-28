<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->date('date')->index();

            // Lo planificado (Copiado del template o personalizado)
            $table->dateTime('scheduled_start');
            $table->dateTime('scheduled_end');

            // Estado del turno
            $table->string('status')->default('scheduled'); // scheduled, absent, day_off, leave
            $table->boolean('is_published')->default(false);

            $table->timestamps();

            // Índice único: Un empleado no puede tener 2 turnos el mismo día
            $table->unique(['employee_id', 'date']);
        });

        // Tabla detalle para descansos (Para poder medir adherencia exacta)
        Schema::create('schedule_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->string('activity_type'); // 'lunch', 'break', 'training'
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration_minutes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('schedule_activities');
        Schema::dropIfExists('schedules');
    }
};
