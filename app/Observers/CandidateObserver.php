<?php

namespace App\Observers;

use App\Services\DBService;

use App\Models\VIP\{
    Candidate,
    TestToPerform,
    AccreditationOfferRequirement,
    CandidateInductionTraining,
    PendingInterview,
    Interview,
    PhysicalTest
};

class CandidateObserver
{
    protected $DBService;

    public function __construct()
    {
        $this->DBService = new DBService;
    }

    /**
     * Handle the Candidate "created" event.
     */
    public function created(Candidate $candidate): void
    {
        $offer = $candidate->OFERTA;

        $exams = TestToPerform::where('OFERTA', $offer)->get()->toArray();

        foreach ($exams as $exam) {
            $data = [
                'OFERTA' => $offer,
                'CANDI' => $candidate->COD,
                'NOM' => get_array_value($exam, 'NOM'),
                'EXCLUY' => get_array_value($exam, 'EXCLUY')
            ];

            switch (get_array_value($exam, 'TIPO')) {
                case '1':
                    $data['TIPO'] = 2;
                    $data['NOM'] = 'Reconocimiento médico autorizado';
                    break;
                case '2':
                    $physicalTest = PhysicalTest::where('COD', get_array_value($exam, 'PRUFIS'))->first();

                    if(isset($physicalTest->NOM)){
                        $data['NOM'] =  $physicalTest->NOM;
                    }

                    $data['TIPO'] = 3;
                    $data['PRUFIS'] = get_array_value($exam, 'PRUFIS');
                    break;
                case '3':
                    $data['TIPO'] = 4;
                    break;
                case '4':
                    $data['TIPO'] = 5;
                    $data['EXAMEN'] = get_array_value($exam, 'EXAMEN');
                break;
            }

            $this->DBService->insert(AccreditationOfferRequirement::class, [$data], increments: ['LIN'], wheres: ['OFERTA' => $offer, 'CANDI' => $candidate->COD]);
        }

        $pendingInterviews = PendingInterview::where('PRUEBA', $offer)->get()->toArray();

        foreach ($pendingInterviews as $pendingInterview) {
            $data = [
                'EXCLUY' => get_array_value($pendingInterview, 'EXCLUY')
            ];

            switch (get_array_value($pendingInterview, 'TIPO')) {
                case '1':
                    $data['TIPO'] = 6;
                    $data['NOM'] = 'Reconocimiento médico autorizado';
                    break;
                case '2':
                    $interview = Interview::where('COD', get_array_value($pendingInterview, 'ENTREV'))->first();

                    $data['TIPO'] = 7;
                    $data['ENTREV'] = get_array_value($pendingInterview, 'ENTREV');
                    $data['NOM'] = $interview->NOM ?? get_array_value($pendingInterview, 'NOM');
                    break;
            }

            $this->DBService->insert(AccreditationOfferRequirement::class, [$data], increments: ['LIN'], wheres: ['OFERTA' => $offer, 'CANDI' => $candidate->COD]);
        }

        $exams = CandidateInductionTraining::where('OFERTA', $offer)->get()->toArray();

        foreach ($exams as $exam) {
            $data = [
                'OFERTA' => $offer,
                'CANDI' => $candidate->COD,
                'TIPO' => 12,
            ];

            switch (get_array_value($exam, 'TIPO')) {
                case '1':
                    $data['NOM'] = 'Test de Wonderlic';
                    $data['EXAMEN'] = 1;
                    break;
                case '2':
                    $data['NOM'] = 'Test de 16PF';
                    $data['EXAMEN'] = 2;
                    break;
                case '3':
                    $data['NOM'] = 'Test de Disc/Cleaver';
                    $data['EXAMEN'] = 3;
                    break;
                case '4':
                    $data['NOM'] = 'Test de Inteligencia Emocional';
                    $data['EXAMEN'] = 4;
                    break;
            }

            $data['EXCLUY'] = get_array_value($exam, 'EXCLUY');

            $this->DBService->insert(AccreditationOfferRequirement::class, [$data], increments: ['LIN'], wheres: ['OFERTA' => $offer, 'CANDI' => $candidate->COD]);
        }
    }
}
