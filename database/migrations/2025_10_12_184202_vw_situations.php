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
            "CREATE OR REPLACE VIEW vw_situations AS
            SELECT s.*, 
            vwpi.id as pi_id, vwpi.name, vwpi.plast_name, vwpi.mlast_name, vwpi.gender,vwpi.email, vwpi.phone, vwpi.curp, vwpi.birthdate, vwpi.community_id, vwpi.street, vwpi.num_ext, vwpi.num_int, 
            vwpi.img_ine, vwpi.img_photo, vwpi.validity, vwpi.full_name, vwpi.full_gender, 
            vwsc.id as sc_id, vwsc.subcategory, vwsc.subcategory_description, vwsc.category_id, vwsc.category, vwsc.category_description, vwsc.department_id, vwsc.letters, vwsc.department, vwsc.department_description,
            ur.username
            FROM situations s
            INNER JOIN vw_personal_info vwpi ON s.personal_info_id=vwpi.id
            INNER JOIN vw_subcategories vwsc ON s.subcategory_id=vwsc.id
            INNER JOIN users ur ON s.registered_by=ur.id
            "
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