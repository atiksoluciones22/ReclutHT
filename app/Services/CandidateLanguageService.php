<?php

namespace App\Services;

use App\Models\VIP\{CandidateLanguage, RequiredLanguagePostulationOffer};

class CandidateLanguageService
{
    protected $DBService;

    public function __construct()
    {
        $this->DBService = new DBService;
    }

    public function run($Languages, $candidateCod, $offers = [])
    {
        foreach ($offers as $offer) {
            $this->candidateLanguage($Languages, $candidateCod, $offer);
        }
    }

    public function candidateLanguage($Languages, $candidateCod, $offer)
    {
        foreach ($Languages as $lenguage) {
            $level = get_array_value($lenguage, 'NIVACR');
            $lenguageValue = get_array_value($lenguage, 'IDIOMA');

            $exists = CandidateLanguage::select('CANDID')->where('CANDID', $candidateCod)->where('IDIOMA', $lenguageValue)->where('OFERTA', $offer)->exists();

            if(!$exists){
                $this->DBService->insert(CandidateLanguage::class, [$lenguage], increments: [],
                append: ['CANDID' => $candidateCod, 'NAESC' => $level, 'NALEI' => $level, 'OFERTA' => $offer]);
            }

            $RequiredLanguagePostulationOffer = RequiredLanguagePostulationOffer::select('COD')->where('OFERTA', $offer)->where('COD', $lenguageValue)->first();

            if($RequiredLanguagePostulationOffer) {
                $requiredLevels = [
                    $RequiredLanguagePostulationOffer->NIVHAB,
                    $RequiredLanguagePostulationOffer->NIVLEI,
                    $RequiredLanguagePostulationOffer->NIVESC
                ];

                // ESTA FUNCION VERIFICA SI EL NIVEL ES MAYOR O IGUAL AL REQUERIDO
                $isQualified = array_reduce($requiredLevels, function($carry, $requiredLevel) use ($level) {
                    return $carry && $level >= $requiredLevel;
                }, true);

                if ($isQualified) {
                    CandidateLanguage::where('OFERTA', $offer)->where('CANDID', $candidateCod)->where('IDIOMA', $lenguageValue)->update([
                        'NIVEXI' => $level,
                        'NELEI' => $level,
                        'NEEXI' => $level,
                        'CUMPLE' => '*',
                    ]);
                }
            }
        }
    }
}
