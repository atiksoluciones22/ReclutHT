<?php

namespace App\Livewire;

use Livewire\Component;

class Timer extends Component
{
    public $timeRemaining;

    protected $listeners = ['decrementTime'];

    public function mount($duration)
    {
        $this->timeRemaining = $duration;
    }

    public function decrementTime()
    {
        if ($this->timeRemaining > 0  ) {
            $this->timeRemaining--;
        }else{
            $this->dispatch('submit');
        }
    }

    public function render()
    {
        return view('livewire.timer');
    }
}
