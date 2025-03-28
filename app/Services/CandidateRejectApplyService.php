<?php

namespace App\Services;

use App\Models\VIP\Candidate;

class CandidateRejectApplyService
{
    public function run($cod)
    {
        $user = auth()->user();

        $candidate = Candidate::where('OFERTA', $cod)->where('COD', $user->COD)->where('CEDULA', $user->CEDULA);

        if (in_array($candidate->first()->SITUAC, [3,4,5])) {
            return false;
        }

       return $candidate->update(['SITUAC' => 6]);
    }
}
