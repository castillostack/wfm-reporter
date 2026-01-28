<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('shift_swaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('employees');
            $table->foreignId('recipient_id')->constrained('employees');

            $table->foreignId('requester_schedule_id')->constrained('schedules');
            $table->foreignId('recipient_schedule_id')->constrained('schedules');

            // Workflow State Machine
            $table->string('status')->default('pending_recipient');
            // pending_recipient -> pending_approval -> approved -> rejected

            $table->foreignId('approved_by')->nullable()->constrained('users'); // Quién aprobó
            $table->dateTime('approved_at')->nullable();

            $table->timestamps();
        });

        // xxxx_xx_xx_create_leaves_table.php
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();

            $table->enum('type', [
                'shift_swap',      // Cambio de turno
                'vacation',        // Vacaciones
                'sick_leave',      // Permiso médico
                'personal',        // Permiso personal
                'unpaid'           // Permiso sin goce
            ]);

            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->text('reason')->nullable();
            $table->string('attachment_path')->nullable(); // Para subir constancia médica

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'cancelled'
            ])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('shift_swaps');
        Schema::dropIfExists('leaves');
    }
};
