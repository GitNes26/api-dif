<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivingConditionsData extends Model
{
    use HasFactory;

    /**
     * Los atributos que se solicitan y se guardan con la funcion fillable() en el controlador.
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'situation_id',
        'house',
        'rooms',
        'living',
        'dining',
        'breakfast_nook',
        'bedroom',
        'house_material',
        'stove',
        'water_service',
        'electricity_service',
        'drainage_service',
        'fosa_service',
        'fecalismo_service',
        'active',
    ];

    /**
     * Nombre de la tabla asociada al modelo.
     * @var string
     */
    protected $table = 'living_conditions_data';

    /**
     * LlavePrimaria asociada a la tabla.
     * @var string
     */
    protected $primaryKey = 'id';
}
