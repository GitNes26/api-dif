<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VW_User extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'vw_users';

    /**
     * LlavePrimaria asociada a la tabla.
     * @var string
     */
    protected $primaryKey = 'id';
}
