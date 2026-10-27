<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_report_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_report_id')
                ->constrained('daily_reports')
                ->cascadeOnDelete();

            $table->foreignId('daily_report_field_id')
                ->constrained('daily_report_fields')
                ->restrictOnDelete();

            $table->longText('value')->nullable();

            $table->timestamps();

            $table->unique(
                ['daily_report_id', 'daily_report_field_id'],
                'daily_report_field_value_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_report_values');
    }
};
