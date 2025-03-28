<?php

namespace App\Services;

use App\Models\VIP\{Candidate, TalentData};

class DBService
{
    /**
     * Inserta datos en un modelo, generando automáticamente valores incrementales para columnas especificadas.
     *
     * @param string $model El modelo en el que insertar los datos.
     * @param array $data Los datos a insertar en el modelo.
     * @param array $wheres Los criterios de búsqueda para seleccionar los registros que se utilizarán para generar los valores incrementales.
     * @param array $increments Las columnas para las que se generarán valores incrementales.
     * @param array $append Los datos adicionales que se insertarán en el modelo.
     * @return void
     */
    public function insert($model, $array, $wheres = [], $increments = ['COD'], $append = [], $returnData = false)
    {
        foreach ($array as $data) {
            $incrementValues = [];

            foreach ($increments as $column) {
                $incrementValues[$column] = $model::query()
                    ->where($wheres)
                    ->max($column) + 1;
            }

            $data = array_merge($data, $incrementValues, $append);

            if($returnData) return $model::create($data);

            $model::insert($data);
        }
    }


    public function maxCode()
    {
        $maxCandidateCod = Candidate::max('COD');

        $maxTalentDataCod = TalentData::max('COD');

        return max($maxCandidateCod, $maxTalentDataCod) + 2;
    }
}
