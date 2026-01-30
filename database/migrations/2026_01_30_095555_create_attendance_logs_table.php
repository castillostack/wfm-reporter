<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained()->nullOnDelete();

            // Tiempos reales
            $table->dateTime('check_in_time');
            $table->dateTime('check_out_time')->nullable();

            // Estado y cálculo
            $table->enum('status', [
                'present',      // Presente a tiempo
                'late',         // Llegó tarde
                'absent',       // Ausente
                'justified',    // Justificado (vacaciones, permiso)
                'partial'       // Asistencia parcial
            ])->default('present');

            $table->integer('late_minutes')->default(0);
            $table->integer('worked_minutes')->nullable(); // Minutos efectivamente trabajados

            // Metadatos
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // Para registros manuales
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // Índices
            $table->index(['employee_id', 'check_in_time']);
            $table->index('schedule_id');
            $table->index('status');
            $table->index('check_in_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('attendance_logs');
    }
};
