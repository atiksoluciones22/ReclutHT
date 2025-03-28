<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\PostulationOfferService;
use App\Models\VIP\PostulationOffer;

class PostulationList extends Component
{
    public $setting, $byUser = false;

    function show($cod) {
        $this->dispatch('show', $cod);
    }

    public function render()
    {
        $rejectedUserPostulationOffers = [];

        if(auth()->check()) {
            $PostulationOfferService = new PostulationOfferService();
            $rejectedUserPostulationOffers = $PostulationOfferService->rejectedUserPostulationOffers();
        }

        if($this->byUser){
            $PostulationOffers = $PostulationOfferService->listUserPostulationOffers();
        }else{
            $PostulationOffers = PostulationOffer::whereIn('SITOFE', [2, 3, 4])->select('COD', 'NOM', 'COMP', 'SALARI', 'TURNO', 'DESPUB')->get()->toArray();
        }

        return view('livewire.postulation-list', compact('PostulationOffers', 'rejectedUserPostulationOffers'));
    }
}
