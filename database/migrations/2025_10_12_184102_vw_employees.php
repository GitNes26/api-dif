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
            "CREATE OR REPLACE VIEW vw_employees AS 
            SELECT e.*, vwu.username, vwu.email, vwu.role_id, vwu.role, vwu.read, vwu.create, vwu.update, vwu.delete, vwu.more_permissions, vwu.page_index, vww.workstation, vww.department_id, vww.letters, vww.department, vww.department_description 
            FROM employees e
            INNER JOIN vw_users vwu ON e.user_id=vwu.id
            INNER JOIN vw_workstations vww ON e.workstation_id=vww.id;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_employee');
    }
};
