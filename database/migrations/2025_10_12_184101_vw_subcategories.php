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
            "CREATE OR REPLACE VIEW vw_subcategories AS 
            SELECT sc.*, vwc.category, vwc.category_description, vwc.department_id, vwc.letters, vwc.department, vwc.department_description FROM subcategories sc INNER JOIN vw_categories vwc ON sc.category_id=vwc.id;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_subcategories');
    }
};