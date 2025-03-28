<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExtendModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = null;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
    */
    protected $guarded = [];
}
