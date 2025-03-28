<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\UpdateCandidateService;

class CandateController extends Controller
{
    protected $UpdateCandidateService;

    public function __construct()
    {
        $this->UpdateCandidateService = new UpdateCandidateService;
    }

    public function update()  {
        try {
            DB::beginTransaction();
                $this->UpdateCandidateService->run(request()->all());
            DB::commit();
            return redirect()->route('home')->with('success', 'Su información ha sido actualizada correcatemente.');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
