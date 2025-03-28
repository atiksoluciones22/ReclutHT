<?php

namespace App\Services;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\VIP\{
    Candidate,
    CandidateDocumentManagement,
    CandidateLanguage,
    CandidateExperience,
    CandidateReference,
    TalentData,
    DocumentManagementTalentData,
    ReferenceDataTalent,
    ExperienceTalentData,
    AccreditedLanguageDataTalent
};

class CandidateAuthApplyService
{
    protected $CandidateLanguageService, $DBService;

    public function __construct()
    {
        $this->CandidateLanguageService = new CandidateLanguageService;
        $this->DBService = new DBService;
    }

    public function run($newOffer)
    {
        $candidateCod = auth()->user()->COD;
        $currentOffer = auth()->user()->OFERTA;

        $candidateQuery = Candidate::where('COD', $candidateCod)->where('OFERTA', $newOffer);

        if ($candidateQuery->exists()) {
            return $candidateQuery->update(['SITUAC' => 1]);
        }

        $newValues = ['OFERTA' => $newOffer];

        if (is_null($currentOffer)) {
            $newCandidate = $this->createNewCandidate($candidateCod, $newValues);
            $newCandidateCod = $newCandidate->COD;

            $this->replicateCandidateDetails($candidateCod, $newCandidateCod, $newOffer, $newValues);
            $this->switchUser($newCandidate);
        } else {
            $this->updateExistingCandidate($candidateCod, $currentOffer, $newOffer, $newValues);
        }
    }

    private function switchUser($newCandidate){
        $user = User::where('EMAIL', $newCandidate->EMAIL)->where('CEDULA', $newCandidate->CEDULA)->first();
        Auth::login($user);
    }

    private function createNewCandidate($candidateCod, $newValues)
    {
        return (object) $this->replicateCandidateData(
            TalentData::class,
            Candidate::class,
            [],
            ['COD' => $candidateCod],
            array_merge($newValues, ['SITUAC' => 1]),
            returnData: true
        );
    }

    private function replicateCandidateDetails($candidateCod, $newCandidateCod, $newOffer, $newValues)
    {
        $newValues = array_merge($newValues, ['CANDID' => $newCandidateCod]);
        $destinationWheres = ['OFERTA' => $newOffer, 'CANDID' => $newCandidateCod];
        $originalWheres = ['CANDID' => $candidateCod];

        $this->replicateCandidateData(
            DocumentManagementTalentData::class,
            CandidateDocumentManagement::class,
            $destinationWheres,
            $originalWheres,
            $newValues
        );

        $languages = AccreditedLanguageDataTalent::where('CANDID', $candidateCod)->get()->toArray();
        $this->CandidateLanguageService->run($languages, $newCandidateCod, [$newOffer]);

        $this->replicateCandidateData(
            ReferenceDataTalent::class,
            CandidateReference::class,
            $destinationWheres,
            $originalWheres,
            $newValues
        );

        $this->replicateCandidateData(
            ExperienceTalentData::class,
            CandidateExperience::class,
            $destinationWheres,
            $originalWheres,
            $newValues
        );
    }

    private function updateExistingCandidate($candidateCod, $currentOffer, $newOffer, $newValues)
    {
        $originalWheres = ['OFERTA' => $currentOffer, 'CANDID' => $candidateCod];
        $destinationWheres = ['OFERTA' => $newOffer, 'CANDID' => $candidateCod];

        $this->replicateCandidateData(
            Candidate::class,
            Candidate::class,
            [],
            ['OFERTA' => $currentOffer, 'COD' => $candidateCod],
            array_merge($newValues, ['SITUAC' => 1]),
            [],
            returnData: true
        );

        $this->replicateCandidateData(
            CandidateDocumentManagement::class,
            CandidateDocumentManagement::class,
            $destinationWheres,
            $originalWheres,
            $newValues
        );

        $languages = CandidateLanguage::where('CANDID', $candidateCod)->get()->toArray();
        $this->CandidateLanguageService->run($languages, $candidateCod, [$newOffer]);

        $this->replicateCandidateData(
            CandidateExperience::class,
            CandidateExperience::class,
            $destinationWheres,
            $originalWheres,
            $newValues
        );

        $this->replicateCandidateData(
            CandidateReference::class,
            CandidateReference::class,
            $destinationWheres,
            $originalWheres,
            $newValues
        );
    }

    /**
     * Replica datos del modelo
     *
     * @param string $model El nombre de la clase del modelo donde se replicarán los datos.
     * @param array $where Un array asociativo de condiciones para seleccionar datos del modelo.
     * @param array $update Un array asociativo de campos y sus valores para actualizar en los nuevos registros.
     * @return void
     */
    private function replicateCandidateData($originalModel, $destinationModel, $destinationWheres, $originalWheres, $update, $increments = ['COD'], $returnData = false)
    {
        // Obtener columnas comunes entre modelos
        $commonColumns = array_intersect(
            Schema::getColumnListing((new $originalModel())->getTable()),
            Schema::getColumnListing((new $destinationModel())->getTable())
        );

        $originalRecords = $originalModel::query()->where($originalWheres)->select($commonColumns)->get();

        foreach ($originalRecords as $record) {
            $newRecord = $record->replicate()->fill($update)->toArray();
            $record = $this->DBService->insert($destinationModel, [$newRecord], $destinationWheres, $increments, returnData: $returnData);
            if($returnData) return $record;
        }
    }
}
