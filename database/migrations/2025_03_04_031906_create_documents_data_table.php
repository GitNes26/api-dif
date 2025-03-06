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
        Schema::create('documents_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('situation_id')->references('situations');
            $table->string('name_doc');
            $table->string('description_doc');
            $table->string('img_doc', 255);
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
        Schema::dropIfExists('documents_data');
    }
};