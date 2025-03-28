<?php

namespace App\Services;

use App\Models\VIP\{
    Candidate,
    CandidateDocumentManagement,
    CandidateExperience,
    CandidateReference,
};

class CandidateApplyService
{
    protected $Handler, $DBService, $FileService, $CandidateAuthApplyService, $CandidateLanguageService, $MailService, $RequestService;

    public function __construct()
    {
        $this->Handler = new Handler;
        $this->DBService = new DBService;
        $this->FileService = new FileService;
        $this->CandidateAuthApplyService = new CandidateAuthApplyService;
        $this->CandidateLanguageService = new CandidateLanguageService;
        $this->MailService = new MailService;
        $this->RequestService = new RequestService;
    }

    public function run($request)
    {
        $bidRequests = $this->RequestService->getBidRequests($request); // ! TODO: PERMITIR APLICAR MAS DE UNA SOLICITUD EN UNA PETICION

        $offer = get_array_value($request, 'cod');

        if(auth()->check()){
            return $this->CandidateAuthApplyService->run($offer);
        }

        $talentData = $this->RequestService->getTalentData($request, ['OFERTA' => $offer, 'DEINTE' => '*']);

        if(Candidate::where('OFERTA', $offer)->where('CEDULA', $talentData['CEDULA'])->exists()){
            dd('redirect, y decirle que ya hiciste una postulacion a esa oferta');
        }

        $maxCode = $this->DBService->maxCode();

        $this->DBService->insert(Candidate::class, [$talentData], increments: [], append: ['COD' => $maxCode], returnData: true);

        // Skills
        $skills = $this->RequestService->getSkills($request);

        for ($i=0; $i < count($skills); $i++) {
            Candidate::where('COD', $maxCode)->where('OFERTA', $offer)->update(['HAB' . $i + 1 => $skills[$i]['HAB']]);
        }

        // Curriculum
        $this->DBService->insert(CandidateDocumentManagement::class,
        [
            [
                'OFERTA' => $offer,
                'CANDID' => $maxCode,
                'NOM' => 'Curriculum vitae',
                'TIPO' => 'Documento web',
                'PORFTP' => '*',
                'RUTFTP' => $this->FileService->uploadFile(get_array_value($request, 'curriculum'), $maxCode . "_" . get_array_value($request, 'cod')),
            ]
        ], wheres: ['CANDID' => $maxCode, 'OFERTA' => $offer]);


        // Languages
        $Languages = $this->RequestService->getLanguages($request);

        $this->CandidateLanguageService->run($Languages, $maxCode, [$offer]);

        // Experience
        $experiences = $this->RequestService->getExperiences($request);
        $this->DBService->insert(CandidateExperience::class, $experiences, wheres:['OFERTA' => $offer, 'CANDID' => $maxCode], append: ['CANDID' => $maxCode, 'OFERTA' => $offer]);

        // References
        $References = $this->RequestService->getReferences($request);

        foreach ($References as &$reference) {
            $reference['TELEFO'] = only_numbers(get_array_value($reference, 'TELEFO'));
        }

        $this->DBService->insert(CandidateReference::class, $References, wheres:['OFERTA' => $offer, 'CANDID' => $maxCode], append: ['CANDID' => $maxCode, 'OFERTA' => $offer]);

        // Send email
        $setting = \App\Models\Setting::firstOrFail();

        $this->MailService->send(to: $talentData['EMAIL'], subject: 'Postulación aceptada', message: $setting->email_postulacion);
    }
}
