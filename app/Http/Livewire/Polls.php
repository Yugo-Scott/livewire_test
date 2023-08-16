<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Option;

class Polls extends Component
{
    public $polls;

    protected $listeners = [
            'pollCreated' => 'render',
    ];
    public function render()
    {
        $this->polls = \App\Models\Poll::with('options.votes')->latest()->get();
        return view('livewire.polls');
    }

    public function vote(Option $option)
    {
        // $option = \App\Models\Option::find($optionId);
        $option->votes()->create();
    }
}
