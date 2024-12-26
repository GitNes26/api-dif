<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(
            "CREATE PROCEDURE `sp_affairs_by_department`(in in_department_id int)
            BEGIN
                SELECT * FROM vw_subcategories
                WHERE department_id=in_department_id;
            END
            "
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::statement('DROP VIEW IF EXISTS vw_situations');
    }
};