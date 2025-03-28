<?php

namespace App\Livewire;

use Livewire\Component;

class Cover extends Component
{
    public $title, $subtitle = null;

    public function render()
    {
        return view('livewire.cover');
    }
}
