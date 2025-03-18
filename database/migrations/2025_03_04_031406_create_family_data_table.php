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
        Schema::create('family_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('situation_id')->constrained('situations');
            $table->string('full_name', 150);
            $table->integer('age');
            $table->string('relationship', 100)->comment('Relacion que tiene el recidente con el solicitante');
            $table->string('civil_status');
            $table->string('occupation', 100);
            $table->string('schooling', 100)->comment('escolaridad');
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_data');
    }
};