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
        Schema::create('situations', function (Blueprint $table) {
            $table->id();
            $table->string("folio")->unique()->comment("su estrucutura sera, letras del departamento, guion medio, numeracion por departamento, ej. PR-1");
            $table->foreignId('personal_info_id')->constrained('personal_info');
            $table->foreignId('subcategory_id')->constrained('subcategories');
            $table->foreignId('registered_by')->constrained('users')->comment("es el usuario del empleado que registra el caso en recepción.");
            $table->text("description")->nullable();
            $table->enum("status", ["abierta", "en_seguimiento", "cerrada"]);
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
        Schema::dropIfExists('situations');
    }
};