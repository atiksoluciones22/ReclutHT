<?php

namespace Shared\Traits;

trait EnumerableTrait
{
    abstract public static function cases();

    public static function names()
    {
        return array_column(static::cases(), 'name');
    }

    public static function values()
    {
        return array_column(static::cases(), 'value');
    }

    public static function get($value = null)
    {
        $data =  array_combine(static::names(), static::values());

        if ($value) {
            foreach ($data as $key => $val) {
                if ($value == $val) {
                    $result = $key;
                }
            }
        }

        $key = isset($result) ? $result : $value;
        return  __("constants.$key");
    }
}
