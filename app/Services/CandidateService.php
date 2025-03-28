<?php

namespace App\Services;

use App\Models\VIP\{
    Candidate,
    CandidateDocumentManagement,
    CandidateLanguage,
    CandidateExperience,
    CandidateReference,

    TalentData,
    DocumentManagementTalentData,
    ExperienceTalentData,
    AccreditedLanguageDataTalent,
    ReferenceDataTalent,
};

class CandidateService
{
    public function profile()
    {
        $user = auth()->user();

        $candidate = Candidate::where(['CEDULA' => $user->CEDULA, 'EMAIL' => $user->EMAIL])->first();

        if($candidate) {
            $profile = $candidate->toArray();
            $profile['candidateDocumentManagements'] = CandidateDocumentManagement::where(['OFERTA' => $candidate->OFERTA, 'CANDID' => $candidate->COD])->first()->toArray();
            $profile['candidateLanguages'] = CandidateLanguage::where(['OFERTA' => $candidate->OFERTA, 'CANDID' => $candidate->COD])->get()->toArray();
            $profile['candidateExperiences'] = CandidateExperience::where(['OFERTA' => $candidate->OFERTA, 'CANDID' => $candidate->COD])->get()->toArray();
            $profile['candidateReferences'] = CandidateReference::where(['OFERTA' => $candidate->OFERTA, 'CANDID' => $candidate->COD])->get()->toArray();
        }else{
            $profile = TalentData::where(['CEDULA' => $user->CEDULA, 'EMAIL' => $user->EMAIL])->first();
            $profile['candidateDocumentManagements'] = DocumentManagementTalentData::where(['CANDID' => $profile->COD])->first()->toArray();
            $profile['candidateLanguages'] = AccreditedLanguageDataTalent::where(['CANDID' => $profile->COD])->get()->toArray();
            $profile['candidateExperiences'] = ExperienceTalentData::where(['CANDID' => $profile->COD])->get()->toArray();
            $profile['candidateReferences'] = ReferenceDataTalent::where(['CANDID' => $profile->COD])->get()->toArray();
        }

        return $profile;
    }
}
