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
        Schema::create('personal_info', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('plast_name');
            $table->string('mlast_name');
            $table->enum('gender', ["H", "M"]);
            $table->string('email');
            $table->string('phone');
            $table->string('curp');
            $table->date('birthdate');
            $table->integer('community_id');
            $table->string('street');
            $table->string('num_ext')->default("S/N");
            $table->string('num_int')->nullable();
            $table->string('img_ine')->nullable();
            $table->string('img_photo')->nullable();
            // $table->string('voter_key');
            // $table->string('section');
            // $table->string('year_registration');
            $table->string('validity');
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
        Schema::dropIfExists('users');
    }
};
