<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VIP\PostulationOffer;
use App\Services\PostulationOfferService;

class PostulationShow extends Component
{
    public $cod;

    protected $listeners = ['show' => 'show'];

    public function show($cod)
    {
        $this->cod = $cod;
    }

    public function close()
    {
        $this->cod = null;
    }

    public function render()
    {
        $postulation = null;

        $rejectedUserPostulationOffers = [];

        if(auth()->check()) $rejectedUserPostulationOffers = (new PostulationOfferService())->rejectedUserPostulationOffers();

        if ($this->cod) {
            $postulation = PostulationOffer::where('COD', $this->cod)->firstOrFail();
        }

        return view('livewire.postulation-show', compact('postulation', 'rejectedUserPostulationOffers'));
    }
}
