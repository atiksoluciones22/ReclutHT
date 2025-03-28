<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\{
    CandidateApplyService,
    CandidateRegistrationService,
    CandidateRejectApplyService
};
use App\Models\VIP\{Company, PostulationOffer};

class PostulationController extends Controller
{
    protected $CandidateRegistrationService, $CandidateApplyService, $CandidateRejectApplyService;

    public function __construct()
    {
        $this->CandidateRegistrationService = new CandidateRegistrationService;
        $this->CandidateApplyService = new CandidateApplyService;
        $this->CandidateRejectApplyService = new CandidateRejectApplyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('postulation.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function apply()
    {
        if(auth()->check()) return redirect()->route('home');

        $title = "Bolsa de trabajo";

        if(request('cod') > 0){
            $PostulationOffer = PostulationOffer::whereIn('SITOFE', [2, 3, 4])->where('COD', request('cod'))->select('NOM', 'COMP')->first();
            $Company = Company::where('COD', $PostulationOffer->COMP)->select('NOM')->first();
            $title = "Postularse a $PostulationOffer->NOM  en $Company->NOM";
        }
        return view('postulation.apply', compact('title'));
    }

    public function saveApply()
    {
        try {
            DB::beginTransaction();
                switch (request('cod')) {
                    case 0: $this->CandidateRegistrationService->run(request()->all()); break;
                    default: $this->CandidateApplyService->run(request()->all()); break;
                }
            DB::commit();
            return redirect()->route('home')->with('success', 'Su información ha sido guardada correcatemente. En breve nos pondremos en contacto con usted.');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }

    public function rejectApply($cod)
    {
        try {
            DB::beginTransaction();
                if(!$this->CandidateRejectApplyService->run($cod)){
                    return redirect()->back()->with('error', 'No se pudo destimular a tu postulación.');
                }
            DB::commit();

            return redirect()->back()->with('success', 'Se destimuló a tu postulación correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
