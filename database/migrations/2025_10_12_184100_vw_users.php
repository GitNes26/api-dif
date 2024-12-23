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
            "CREATE OR REPLACE VIEW vw_users AS 
            SELECT u.*, r.role, r.read, r.create, r.update, r.delete, r.more_permissions, r.page_index 
            FROM users u 
            INNER JOIN roles r ON u.role_id=r.id;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_users');
    }
};
