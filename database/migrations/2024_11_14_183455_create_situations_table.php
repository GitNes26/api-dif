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
            $table->string('beneficiary', 255)->nullable();
            $table->integer('beneficiary_age')->nullable();
            $table->foreignId('subcategory_id')->constrained('subcategories');
            $table->text("description")->nullable();
            $table->text("support")->nullable();
            $table->enum("status", ["abierto", "en_seguimiento", "cerrado", "cancelado"]);
            $table->boolean('family_data_finish')->nullable();
            $table->boolean('living_conditions_data_finish')->nullable();
            $table->boolean('economic_data_finish')->nullable();
            $table->boolean('documents_data_finish')->nullable();
            $table->boolean('evidences_data_finish')->nullable();
            $table->boolean('finish')->nullable();
            $table->string('img_firm_requester')->nullable();

            $table->integer('current_page')->default(1)->nullable();
            $table->dateTime('end_date')->nullable();

            $table->integer('situation_settings_id')->nullable();
            $table->foreignId('registered_by')->constrained('users')->comment("es el usuario del empleado que registra el caso en recepción.");
            $table->integer('authorized_by')->nullable();
            $table->text('authorized_comment')->nullable();
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
