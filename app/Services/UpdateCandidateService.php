<?php

namespace App\Services;

use App\Models\VIP\{
    Candidate,
    CandidateDocumentManagement,
    CandidateExperience,
    CandidateLanguage,
    CandidateReference,

    TalentData,
    DocumentManagementTalentData,
    ExperienceTalentData,
    AccreditedLanguageDataTalent,
    ReferenceDataTalent
};

class UpdateCandidateService
{
    protected $RequestService, $FileService, $DBService, $CandidateLanguageService;

    public function __construct()
    {
        $this->RequestService = new RequestService;
        $this->DBService = new DBService;
        $this->FileService = new FileService;
        $this->CandidateLanguageService = new CandidateLanguageService;
    }

    public function run($request)
    {
        $user = auth()->user();

        $data = $this->RequestService->getTalentData($request);

        $candidate = Candidate::where(['EMAIL' => $user->EMAIL, 'CEDULA' => $user->CEDULA])->first();

        $talentData = TalentData::where(['EMAIL' => $user->EMAIL, 'CEDULA' => $user->CEDULA])->first();

        $languages = $this->RequestService->getLanguages($request);

        $experiences = $this->RequestService->getExperiences($request);

        $references = $this->RequestService->getReferences($request);

        foreach ($references as &$reference) {
            $reference['TELEFO'] = only_numbers(get_array_value($reference, 'TELEFO'));
        }

        $curriculum = get_array_value($request, 'curriculum');

        $curriculum = $this->FileService->uploadFile($curriculum,  $candidate?->COD . "_" . get_array_value($request, 'cod'));

        $this->updateCandidate($candidate, $data, $user, $languages, $experiences, $references, $curriculum);

        $this->updateTalentData($talentData, $data, $user, $languages, $experiences, $references, $curriculum);
    }


    private function updateCandidate($candidate, $data, $user, $languages, $experiences, $references, $curriculum){
        if($candidate){
            Candidate::where(['EMAIL' => $user->EMAIL, 'CEDULA' => $user->CEDULA])->update($data);
            CandidateExperience::where(['CANDID' => $candidate?->COD])->delete();
            CandidateReference::where(['CANDID' => $candidate?->COD])->delete();
            CandidateLanguage::where(['CANDID' => $candidate?->COD])->delete();
            CandidateDocumentManagement::where(['CANDID' => $candidate?->COD, 'NOM' => "Curriculum vitae"])->update(['RUTFTP' => $curriculum]);

            $offers = Candidate::where(['EMAIL' => $user->EMAIL, 'CEDULA' => $user->CEDULA])->pluck('OFERTA')->toArray();
            $this->DBService->insert(CandidateExperience::class, $experiences, wheres:['OFERTA' => $candidate?->OFERTA, 'CANDID' => $candidate?->COD], append: ['CANDID' => $candidate?->COD, 'OFERTA' => $candidate?->OFERTA]);
            $this->DBService->insert(CandidateReference::class, $references, wheres:['OFERTA' => $candidate?->OFERTA, 'CANDID' => $candidate?->COD], append: ['CANDID' => $candidate?->COD, 'OFERTA' => $candidate?->OFERTA]);
            $this->CandidateLanguageService->run($languages, $candidate?->COD, $offers);
        }
    }

    private function updateTalentData($talentData, $data, $user, $languages, $experiences, $references, $curriculum){
        if($talentData){
            TalentData::where(['EMAIL' => $user->EMAIL, 'CEDULA' => $user->CEDULA])->update($data);
            ExperienceTalentData::where(['CANDID' => $talentData?->COD])->delete();
            ReferenceDataTalent::where(['CANDID' => $talentData?->COD])->delete();
            AccreditedLanguageDataTalent::where(['CANDID' => $talentData?->COD])->delete();
            DocumentManagementTalentData::where(['CANDID' => $talentData?->COD, 'NOM' => "Curriculum vitae"])->update(['RUTFTP' => $curriculum]);

            $this->DBService->insert(ExperienceTalentData::class, $experiences, append: ['CANDID' => $talentData?->COD]);
            $this->DBService->insert(ReferenceDataTalent::class, $references, append: ['CANDID' => $talentData?->COD]);
            foreach ($languages as $lenguage) {
                $this->DBService->insert(AccreditedLanguageDataTalent::class, [$lenguage], increments: [],
                append: ['CANDID' => $talentData?->COD, 'NAESC' => get_array_value($lenguage, 'NIVACR'), 'NALEI' => get_array_value($lenguage, 'NIVACR')]);
            }
        }
    }
}
