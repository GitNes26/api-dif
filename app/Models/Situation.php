<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Situation extends Model
{
    use HasFactory;

    /**
     * Especificar la conexion si no es la por default
     * @var string
     */
    //protected $connection = "db_mysql";

    /**
     * Los atributos que se solicitan y se guardan con la funcion fillable() en el controlador.
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'folio',
        'requester_id',
        'beneficiary_id',
        'subcategory_id',
        'description',
        'support',
        'status',
        // 'family_data', #esta tabla tendra el id de la situacion
        // 'living_conditions_data_id',
        // 'economic_data_id'
        // 'documents_data', #esta tabla tendra el id de la situacion
        // 'evidences_data', #esta tabla tendra el id de la situacion
        'img_firm_requester',
        'situation_settings_id',
        
        'registered_by',
        'authorized_by',
        'authorized_at',
        'follow_up_by',
        'follow_up_at',
        'rejected_by',
        'rejected_comment',
        'rejected_at',
        'active',
    ];

    /**
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'situations';

    /**
     * LlavePrimaria asociada a la tabla.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Obtener los usuarios relacionados a un rol.
     */
    // public function users()
    // {
    //     return $this->hasMany(User::class, 'role_id', 'id'); //primero se declara FK y despues la PK
    // }

    /**
     * Valores defualt para los campos especificados.
     * @var array
     */
    // protected $attributes = [
    //     'active' => true,
    // ];
}