<?php

namespace App\Models\VIP;

use App\Models\ExtendModel;

class MultipleChoiceExam extends ExtendModel
{
    protected $table = '277';

    public function questions() {
        return $this->hasMany(MultipleChoiceExamQuestion::class, 'EXAMEN', 'COD');
    }
}
