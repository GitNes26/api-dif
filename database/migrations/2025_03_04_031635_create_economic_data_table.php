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
        Schema::create('economic_data', function (Blueprint $table) {
            $table->id();
            $table->decimal('monthly_income')->default(0)->comment('Ingresos mensuales');
            $table->decimal('monthly_expenses')->default(0)->comment('Egresos mensuales');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_data');
    }
};
