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
            $table->foreignId('requester_id')->constrained('personal_info');
            $table->foreignId('beneficiary_id')->constrained('personal_info')->nullable();
            $table->foreignId('subcategory_id')->constrained('subcategories');
            $table->text("description")->nullable();
            $table->text("support")->nullable();
            $table->enum("status", ["abierta", "en_seguimiento", "cerrada"]);
            $table->foreignId('living_conditions_data_id')->constrained('living_conditions_data')->nullable();
            $table->foreignId('economic_data_id')->constrained('economic_data')->nullable();
            $table->string('img_firm_requester')->nullable();
            $table->foreignId('registered_by')->constrained('users')->comment("es el usuario del empleado que registra el caso en recepción.");
            $table->integer('authorized_by')->nullable();
            $table->dateTime('authorized_at')->nullable();
            $table->integer('follow_up_by')->nullable();
            $table->dateTime('follow_up_at')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->text('rejected_comment')->nullable();
            $table->dateTime('rejected_at')->nullable();
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
