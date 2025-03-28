<?php

namespace App\Services;

use App\Models\VIP\{
    AccreditedLanguageDataTalent,
    ExperienceTalentData,
    ReferenceDataTalent,
    DocumentManagementTalentData,
    TalentData
};

class CandidateRegistrationService
{
    protected $Handler, $DBService, $FileService, $MailService, $RequestService;

    public function __construct()
    {
        $this->Handler = new Handler;
        $this->DBService = new DBService;
        $this->FileService = new FileService;
        $this->MailService = new MailService;
        $this->RequestService = new RequestService;
    }

    public function run($request)
    {
        $talentData = $this->RequestService->getTalentData($request);

       if(TalentData::where('CEDULA', $talentData['CEDULA'])->where('EMAIL', $talentData['EMAIL'])->exists()){
            dd('Ya existe un candidato con esa cédula');
       }

       $maxCode = $this->DBService->maxCode();

       $this->DBService->insert(TalentData::class, [$talentData], increments: [], append: ['COD' => $maxCode]);

        // Languages
        $Languages = $this->RequestService->getLanguages($request);

        foreach ($Languages as $lenguage) {
            $this->DBService->insert(AccreditedLanguageDataTalent::class, [$lenguage], increments: [],
            append: ['CANDID' => $maxCode, 'NAESC' => get_array_value($lenguage, 'NIVACR'), 'NALEI' => get_array_value($lenguage, 'NIVACR')]);
        }

        // Experience
        $experiences = $this->RequestService->getExperiences($request);
        $this->DBService->insert(ExperienceTalentData::class, $experiences, append: ['CANDID' => $maxCode]);

        // References
        $References = $this->RequestService->getReferences($request);

        foreach ($References as &$reference) {
            $reference['TELEFO'] = only_numbers(get_array_value($reference, 'TELEFO'));
        }

        $this->DBService->insert(ReferenceDataTalent::class, $References, append: ['CANDID' => $maxCode]);

        // Documents
        $this->DBService->insert(DocumentManagementTalentData::class,
        [
            [
                'CANDID' => $maxCode,
                'NOM' => 'Curriculum vitae',
                'TIPO' => 'Documento web',
                'PORFTP' => '*',
                'RUTFTP' => $this->FileService->uploadFile(get_array_value($request, 'curriculum'), $maxCode . "_" . get_array_value($request, 'cod')),
            ]
        ], wheres: ['CANDID' => $maxCode]);

        // Send email
        $setting = \App\Models\Setting::firstOrFail();

        $this->MailService->send(to: $talentData['EMAIL'], subject: 'Candidatura recibida', message: $setting->email_inscripcion);
    }
}
