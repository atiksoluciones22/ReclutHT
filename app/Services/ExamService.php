<?php

namespace App\Services;

use App\Models\SDM\TestQuestion;
use App\Models\VIP\{
    MultipleChoiceExam,
    PsychometricResponse,
    MultipleChoiceExamQuestion,
    TestResult,
    AccreditationOfferRequirement,
    EmotionalTestAnswer,
    CleaverTestAnswer
};

class ExamService
{
    public function processAnswers($answers, $user)
    {
        $where = ['OFERTA' => request('offer'), 'CANDI' => $user->COD, 'LIN' => request('linea')];
        $offerRequirement = AccreditationOfferRequirement::where($where)->first();
        $examCode = $offerRequirement->EXAMEN;

        switch ($offerRequirement->TIPO) {
            case 4:
                $this->handleType4Exam($examCode, $answers, $user);
                break;

            case 5:
                $this->handleType5Exam($examCode, $answers, $user);
                break;

            case 12:
                $this->handleType12Exam($examCode, $answers, $user);
                break;
        }
    }

    private function handleType12Exam($examCode, $answers, $user)
    {
        $data = [
            'OFERTA' => request('offer'),
            'CANDI' => $user->COD,
        ];

        if($examCode == 3){
            $answers = implode('', $answers);

            $data = array_merge($data, ['RESPUE' => $answers]);

            CleaverTestAnswer::insert($data);
        }else{
            foreach ($answers as $key => $value) {
                $pregu = explode('_', $key)[0];

                $posib = explode('_', $key)[1];

                $data = array_merge($data, ['PREGUN' => $pregu, 'RESIND' => $value]);

                switch ($examCode) {
                    case 1:
                        $question = TestQuestion::where(['TEST' => 1, 'PREGU' => $pregu, 'POSIB' => $posib])->first()->toArray();
                        $data['EXAMEN'] = 1;
                        $data['POSIB'] = $posib;
                        $data['RESCOR'] = $question['RESCOR'];
                        $data['ACERT'] = $data['RESCOR'] == $data['RESIND'] ? '*' : '';
                        PsychometricResponse::insert($data);
                        break;

                    case 2:
                        $data['EXAMEN'] = 2;
                        PsychometricResponse::insert($data);
                        break;

                    case 4:
                        unset($data['RESIND']);
                        $data['RESP'] = $value;
                        EmotionalTestAnswer::insert($data);
                        break;
                }
            }
        }
    }

    private function handleType4Exam($examCode, $answers, $user)
    {

    }

    private function handleType5Exam($examCode, $answers, $user)
    {
        $exam = MultipleChoiceExam::where('COD', $examCode)->first();

        foreach ($answers as $key => $value) {
            $question = MultipleChoiceExamQuestion::where(['EXAMEN' => $examCode, 'COD' => $key])->first();

            TestResult::insert([
                "OFERTA" => request('offer'),
                "TIPPRU" => $exam->TIPO,
                "COD" => $exam->COD,
                "CANDI" => $user->COD,
                "LIN" => $key,
                "RES1" => $value,
                "FEC" => date("Ymd"),
                "EXI1" => $question->RESACE,
                "APROB" => $question->RESACE == $value ? '*' : '',
            ]);
        }
    }

    public function getDiscCleaverQuestions()
    {
        return [
            [
                1 => 'Persuasivo',
                2 => 'Gentil',
                3 => 'Humilde',
                4 => 'Original'
            ],
            [
                5 => 'Agresivo',
                6 => 'Alma de la fiesta',
                7 => 'Comodino',
                8 => 'Temeroso'
            ],
            [
                9 => 'Agradable',
                10 => 'Temeroso de Dios',
                11 => 'Tenaz',
                12 => 'Atractivo'
            ],
            [
                13 => 'Cauteloso',
                14 => 'Determinado',
                15 => 'Convincente',
                16 => 'Bonachón'
            ],
            [
                17 => 'Dócil',
                18 => 'Atrevido',
                19 => 'Leal',
                20 => 'Encantador'
            ],
            [
                21 => 'Dispuesto',
                22 => 'Deseoso',
                23 => 'Consecuente',
                24 => 'Entusiasta'
            ],
            [
                25 => 'Fuerza de voluntad ',
                26 => 'Mente abierta',
                27 => 'Complaciente',
                28 => 'Animoso'
            ],
            [
                29 => 'Confiado',
                30 => 'Simpatizador',
                31 => 'Tolerante',
                32 => 'Afirmativo'
            ],
            [
                33 => 'Ecuánime',
                34 => 'Preciso',
                35 => 'Nervioso',
                36 => 'Jovial'
            ],
            [
                37 => 'Disciplinado',
                38 => 'Generoso',
                39 => 'Animoso',
                40 => 'Persistente'
            ],
            [
                41 => 'Competitivo',
                42 => 'Alegre',
                43 => 'Considerado',
                44 => 'Armonioso'
            ],
            [
                45 => 'Admirable',
                46 => 'Bondadoso',
                47 => 'Resignado',
                48 => 'Carácter firme'
            ],
            [
                49 => 'Obediente',
                50 => 'Quisquilloso',
                51 => 'Inconquistable',
                52 => 'Juauetón'
            ],
            [
                53 => 'Respetuoso',
                54 => 'Emprendedor',
                55 => 'Optimista',
                56 => 'Servicial'
            ],
            [
                57 => 'Valiente',
                58 => 'lnspirador',
                59 => 'Sumiso',
                60 => 'Tímido'
            ],
            [
                61 => 'Adaptable',
                62 => 'Disputador',
                63 => 'Indiferente',
                64 => 'Sangre liviana'
            ],
            [
                65 => 'Amiguero',
                66 => 'Paciente',
                67 => 'Confianza en sí mismo',
                68 => 'Mesurado para hablar'
            ],
            [
                69 => 'Conforme',
                70 => 'Confiado',
                71 => 'Pacífico',
                72 => 'Positivo'
            ],
            [
                73 => 'Aventurero',
                74 => 'Receptivo ',
                75 => 'Cordial',
                76 => 'Moderado'
            ],
            [
                77 => 'lndulgente',
                78 => 'Esteta',
                79 => 'Vigoroso',
                80 => 'Sociable'
            ],
            [
                81 => 'Parlanchín',
                82 => 'Controlado',
                83 => 'Convencional',
                84 => 'Decisivo'
            ],
            [
                85 => 'Cohibido',
                86 => 'Exacto',
                87 => 'Franco',
                88 => 'Buen compañero'
            ],
            [
                89 => 'Diplomático',
                90 => 'Audaz',
                91 => 'Refinado',
                92 => 'Satisfecho'
            ],
            [
                93 => 'lnquieto',
                94 => 'Popular',
                95 => 'Buen vecino',
                96 => 'Devoto'
            ],
        ];
    }
}
