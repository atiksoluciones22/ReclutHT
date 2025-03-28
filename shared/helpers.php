<?php
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;

if (!function_exists('get_value_from_json_by_key')) {
    function get_value_from_json_by_key($jsonString, $key) {
        $array = json_decode($jsonString, true);
        return get_array_value($array, $key);
    }
}

if (!function_exists('get_array_value')) {
    function get_array_value($array, $key) {
        return $array[$key] ?? null; // Devuelve null si la clave no existe
    }
}

if (!function_exists('filter_array_by_non_null_fields')) {
    function filter_array_by_non_null_fields($array, $fields) {
        return array_values(array_filter($array, function($item) use ($fields) {
            foreach ($fields as $field) {
                if (!isset($item[$field]) || $item[$field] === null) {
                    return false;
                }
            }
            return true;
        }));
    }
}

if (!function_exists('filter_array_by_unique_fields')) {
    function filter_array_by_unique_fields($array, $fields) {
        $uniqueArray = [];
        $seenValues = [];

        foreach ($array as $item) {
            $isUnique = true;
            foreach ($fields as $field) {
                if (isset($item[$field]) && in_array($item[$field], $seenValues[$field] ?? [])) {
                    $isUnique = false;
                    break;
                }
            }

            if ($isUnique) {
                foreach ($fields as $field) {
                    if (isset($item[$field])) {
                        $seenValues[$field][] = $item[$field];
                    }
                }
                $uniqueArray[] = $item;
            }
        }

        return $uniqueArray;
    }
}

if (!function_exists('only_numbers')) {
    function only_numbers($value) {
        if($value){
            return preg_replace('/[^0-9]/', '', $value);
        }

        return null;
    }

}

if (!function_exists('format_money')) {
    function format_money($value) {
        $number = str_replace(['$', ','], '', $value);
        return number_format((float) $number, 0, '', '');
    }
}

if (!function_exists('format_date')) {
    function format_date($date) {
        if ($date == '') return null;

        try {
            $date = Carbon::createFromFormat('Y-m-d', $date);
        } catch (InvalidFormatException $e) {
            $date = Carbon::createFromFormat('Y-m-d', '1900-01-01');
        }

        // Validate year, month, and day ranges
        $year = $date->year;
        if ($year > 2100 || $year < 1900) {
            $year = 1900;
        }

        $month = $date->month;
        if ($month > 12 || $month < 1) {
            $month = 1;
        }

        $day = $date->day;
        if ($day > 31 || $day < 1) {
            $day = 1;
        }

        // Format the date as 'yyyymmdd'
        return Carbon::create($year, $month, $day)->format('Ymd');
    }
}

if (!function_exists('convert_date')) {
    function convert_date($dateString) {
        $date = DateTime::createFromFormat('Ymd', $dateString);

        if ($date !== false) {
            return $date->format('Y-m-d');
        }

        return null;
    }
}

if (!function_exists('normalize_value')) {
    function normalize_value($value) {
        if ($value === null || $value === 'null' || $value === '' || $value === 'undefined') {
            return null;
        }
        return $value;
    }
}

if (!function_exists('convert_rtf_to_text')) {
    function convert_rtf_to_text($rtfText) {
        try {
            $document = new Document($rtfText);

            $formatter = new HtmlFormatter();

            $html = $formatter->Format($document);

            $plainText = strip_tags($html);

            return decode_html_entities($plainText);
        } catch (\Throwable $th) {
            return $rtfText;
        }
    }
}

if (!function_exists('decode_html_entities')) {
    function decode_html_entities($string)
    {
        return html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    }
}
