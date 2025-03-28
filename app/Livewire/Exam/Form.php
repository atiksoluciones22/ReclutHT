<?php

namespace App\Livewire\Exam;

use Livewire\Component;

class Form extends Component
{
    public $exam, $type, $examcode;

    public $totalQuestions = 0, $currentQuestion = 0;

    protected $listeners = ['submit' => 'submit'];

    public function back(){
        if($this->currentQuestion > 0) $this->currentQuestion = $this->currentQuestion - 1;
    }

    public function submit()
    {
        $this->currentQuestion++;

        if ($this->currentQuestion >= $this->totalQuestions) {
            $this->currentQuestion = $this->totalQuestions - 1;
            $this->dispatch('submitForm');
        }
    }

    public function mount($exam)
    {
        switch ($this->type) {
            case 5:
                $this->exam = $exam;
                $this->totalQuestions = count(get_array_value($exam, 'questions'));
                break;
            case 12:
                $this->totalQuestions = count($exam);
                break;
        }
    }

    public function render()
    {
        $currentQuestion = $this->currentQuestion;
        return view('livewire.exam.form', compact('currentQuestion'));
    }
}
