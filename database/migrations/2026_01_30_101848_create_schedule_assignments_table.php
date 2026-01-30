<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('schedule_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_template_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->json('target_filters'); // {"departments": [1,2], "teams": [3], "employees": [5,6]}
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('schedules_created')->default(0);
            $table->json('assignment_results')->nullable(); // Resultados de la asignación
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('schedule_assignments');
    }
};
