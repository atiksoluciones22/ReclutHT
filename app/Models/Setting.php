<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Builder};

class Setting extends Model
{
    protected $connection = 'mysql';

    protected $table = "configuracion";

    protected static function booted()
    {
        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('cliente', env('CLIENT_NAME'));
        });
    }
}
