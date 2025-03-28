<?php

namespace App\Services;

class Handler
{

/**
 * Agrupa y traduce los valores del array según los prefijos especificados.
 *
 * @param array $request El array de entrada que contiene las claves y valores.
 * @param array $prefixes Un array que mapea los prefijos a sus traducciones.
 * @return array Un array con los valores agrupados y traducidos.
 */
function groupAndTranslateValues(array $request, array $prefixes) {
    // Crear un array para almacenar los valores asociados a cada clave base
    $groupedValues = [];

    // Recorrer cada prefijo
    foreach ($prefixes as $prefix => $translation) {
        // Usar preg_grep para obtener las claves que empiezan con el prefijo actual
        $pattern = '/^' . preg_quote($prefix) . '(\d+).*$/';
        $matchingKeys = preg_grep($pattern, array_keys($request));

        foreach ($matchingKeys as $key) {
            // Extraer el índice numérico de la clave
            preg_match('/\d+/', $key, $matches);
            $index = isset($matches[0]) ? (int)$matches[0] : null;

            if ($index !== null) {
                // Limpiar la clave base (quitar los números y cualquier sufijo adicional)
                $cleanedKey = $translation;

                // Inicializar el array si no existe
                if (!isset($groupedValues[$index])) {
                    $groupedValues[$index] = [];
                }

                // Agregar el valor al array correspondiente a la clave base
                $groupedValues[$index][$cleanedKey] = $request[$key];
            } else {
                // No hay número en la clave, agregar la clave original y su valor
                $groupedValues[] = [$key => $request[$key]];
            }
        }
    }

    // Convertir el resultado en una estructura de arrays
    return array_values($groupedValues);
}
}
