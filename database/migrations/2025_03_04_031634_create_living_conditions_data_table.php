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
        Schema::create('living_conditions_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('situation_id')->constrained('situations');
            $table->string('house')->comment('propia|rentada|prestada|otro');
            $table->integer('rooms');
            $table->boolean('living');
            $table->boolean('dining')->comment('comedor');
            $table->boolean('breakfast_nook')->comment('antecomedor');
            $table->boolean('bedroom')->comment('recamara');
            $table->string('house_material')->comment('adobe|ladrillo|concreto|otro');
            $table->string('stove')->comment('gas|petroleo|leña|otro');
            $table->boolean('water_service')->comment('servicio de agua');
            $table->boolean('electricity_service')->comment('servicio de electicidad');
            $table->boolean('drainage_service')->comment('servicio de drenaje');
            $table->boolean('fosa_service')->comment('servicio de fosa');
            $table->boolean('fecalismo_service')->comment('servicio de fecalismo');
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
        Schema::dropIfExists('living_conditions_data');
    }
};
