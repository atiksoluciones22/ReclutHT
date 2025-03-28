<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use App\Models\SDM\TestQuestion;
use App\Models\VIP\{
    MultipleChoiceExam,
    AccreditationOfferRequirement,
};

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function exam()
    {
        $where = ['OFERTA' => request('offer'), 'CANDI' => auth()->user()->COD, 'LIN' => request('linea')];

        $offerRequirement = AccreditationOfferRequirement::where($where)->select('TIPO', 'EXAMEN')->first();

        $type = $offerRequirement->TIPO;

        $examCode = $offerRequirement->EXAMEN;

        switch ($type) {
            case '5':
                $exam = MultipleChoiceExam::where('COD', $examCode)->with('questions')->first()->toArray();
                $title = get_array_value($exam, 'NOM');
            break;
            case '4':
                dd('pediente de desarrollo');
            break;
            case '12':
                if($examCode == 3){
                    $title = 'Test de Disc/Cleaver';
                    $exam = $this->examService->getDiscCleaverQuestions();
                }else{
                    $exam = array_values(TestQuestion::where('TEST', $examCode)->inRandomOrder('PREGU')->get()->unique('PREGU')->toArray());

                    switch ($examCode) {
                        case 1:
                            $title = 'Test de Wonderlic';
                            break;
                        case 2:
                            $title = 'Test de 16PF';
                            break;
                        case 4:
                            $title = 'Test de Inteligencia Emocional';
                            break;
                    }
                }
            break;
        }

        $testPerformed = AccreditationOfferRequirement::where(array_merge($where, ['REALIZ' => "*"]))->exists();

        if($testPerformed) return redirect()->route('panel');

        AccreditationOfferRequirement::where($where)->update(['REALIZ' => '*']);

        return view('panel.exam.form', compact('exam', 'title', 'type', 'examCode'));
    }

    public function storeExam()
    {
        $answers = request()->except('_token');

        $user = auth()->user();

        $this->examService->processAnswers($answers, $user);

        return redirect()->route('panel')->with('success', '¡Tu prueba ha sido guardada correctamente!');
    }
}
