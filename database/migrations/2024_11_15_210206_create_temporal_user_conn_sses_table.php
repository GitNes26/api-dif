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
        Schema::create('temporal_user_conn_sse', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->references("users");
            $table->foreignId("conn")->references("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporal_user_conn_sse');
    }
};