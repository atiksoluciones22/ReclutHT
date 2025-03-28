<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\VIP\TalentData;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Schema;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "199";

    protected $primaryKey = 'COD';

    public $timestamps = false;

    protected $fillable = [
        'EMAIL',
        'CEDULA',
        'COD'
    ];

    protected $appends = ['full_name', 'first_letter'];

    public function getFullNameAttribute()
    {
        return $this->NOM . ' ' . $this->APE1;
    }

    public function getFirstLetterAttribute()
    {
        return mb_substr($this->NOM, 0, 1);
    }

    protected static function booted()
    {
        $user1Columns = Schema::getColumnListing((new static())->getTable());
        $user2Columns = Schema::getColumnListing((new TalentData())->getTable());
        $commonColumns = array_intersect($user1Columns, $user2Columns);

        static::addGlobalScope('unionWithTalentData', function (Builder $builder) use ($commonColumns) {
            $mainQuery = $builder->getQuery();

            // Construir la subconsulta para TalentData
            $subQuery = TalentData::select($commonColumns)->selectRaw('NULL AS OFERTA');

            // Aplicar las mismas condiciones where del modelo principal al subquery
            $subQuery->where(function ($query) use ($mainQuery) {
                foreach ($mainQuery->wheres as $where) {
                    if (isset($where['column']) && isset($where['operator']) && isset($where['value']) && isset($where['boolean'])) {
                        $query->where($where['column'], $where['operator'], $where['value'], $where['boolean']);
                    }
                }
            });

            // Realizar el union
            $builder->unionAll($subQuery)->select(array_merge($commonColumns, ['OFERTA']));
        });
    }

    public function applications(){
        $applications = $this->where('EMAIL', $this->EMAIL)->where('COD', $this->COD)->pluck('OFERTA')->map(function($item) {
            return (int) $item;
        })->toArray();

        return array_filter($applications, function($item) { return $item > 0; });
    }
}
