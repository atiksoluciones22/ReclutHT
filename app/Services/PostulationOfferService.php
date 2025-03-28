<?php

namespace App\Services;

use App\Models\VIP\{Candidate, PostulationOffer, AccreditationOfferRequirement};

class PostulationOfferService
{
    private $candidateTable, $PostulationOfferTable, $userApplications, $userCod;

    public function __construct() {
        $this->candidateTable = (new Candidate)->getTable();
        $this->PostulationOfferTable = (new PostulationOffer)->getTable();
        $this->userApplications = auth()->user()->applications();
        $this->userCod = auth()->user()->COD;
    }

    protected function baseQuery()
    {
        return PostulationOffer::join($this->candidateTable, "{$this->candidateTable}.OFERTA", '=', "{$this->PostulationOfferTable}.COD")
            ->whereIn('SITOFE', [2, 3, 4])
            ->whereIn("{$this->PostulationOfferTable}.COD", $this->userApplications)
            ->where("{$this->candidateTable}.COD", $this->userCod);
    }

    public function rejectedUserPostulationOffers()
    {
        return $this->baseQuery()
            ->where("{$this->candidateTable}.SITUAC", '=', 6)
            ->select("{$this->PostulationOfferTable}.COD", "{$this->PostulationOfferTable}.NOM", 'COMP', 'SALARI', 'TURNO', 'DESPUB', 'SITUAC')
            ->pluck('COD')
            ->toArray();
    }

    public function listUserPostulationOffers()
    {
       $PostulationOffers = $this->baseQuery()
        ->where("{$this->candidateTable}.SITUAC", '!=', 6)
        ->select("{$this->candidateTable}.COD", "{$this->PostulationOfferTable}.COD as OFERTA", "{$this->PostulationOfferTable}.NOM", 'COMP', 'SALARI', 'TURNO', 'DESPUB', 'SITUAC')
        ->get()->toArray();

        foreach ($PostulationOffers as &$postulationOffer) {
           $postulationOffer['AccreditationOfferRequirement'] = AccreditationOfferRequirement::where([
                'OFERTA' => $postulationOffer['OFERTA'],
                'CANDI' => $postulationOffer['COD'],
                'REALIZ' => null
            ])->get()->toArray();
        }

        return $PostulationOffers;
    }
}
