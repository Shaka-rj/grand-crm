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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // Mijoz
            $table->string('client_name');
            $table->string('phone', 30);

            // Qachon bormoqchi
            $table->string('travel_date')->nullable();

            // Hodim
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Bo‘lim
            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();

            // Status
            $table->foreignId('status_id')
                ->constrained('lead_statuses')
                ->restrictOnDelete();

            // Status oxirgi marta qachon o‘zgargan
            $table->timestamp('status_updated_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_statuses');
    }
};
