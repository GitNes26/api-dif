<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
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
        'workstation',
        'department_id',
        'active'
    ];

    /**
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'workstations';

    /**
     * LlavePrimaria asociada a la tabla.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Obtener departamento asociado con el puesto.
     */
    public function department()
    {   //primero se declara FK y despues la PK del modelo asociado
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Valores defualt para los campos especificados.
     * @var array
     */
    // protected $attributes = [
    //     'active' => true,
    // ];
}
