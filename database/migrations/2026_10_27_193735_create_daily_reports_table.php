<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('report_date');

            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();

            $table->text('comment')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['user_id', 'report_date'],
                'daily_reports_user_date_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
