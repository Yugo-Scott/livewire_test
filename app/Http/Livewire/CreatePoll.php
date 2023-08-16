<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Poll;

class CreatePoll extends Component
{
    public $title;
    public $options = [''];

    protected $rules = [
        'title' => 'required|min:6|max:255',
        'options' => 'required|min:1|array|max:10',
        'options.*' => 'required|min:1|max:255',
    ];  

    protected $messages = [
        'title.required' => 'Poll title is required',
        'title.min' => 'Poll title must be at least 6 characters',
        'title.max' => 'Poll title must be less than 255 characters',
        'options.required' => 'Poll options are required',
        'options.min' => 'Poll options must be at least 1',
        'options.max' => 'Poll options must be less than 10',
        'options.*.required' => 'Poll option is required',
        'options.*.min' => 'Poll option must be at least 1 character',
        'options.*.max' => 'Poll option must be less than 255 characters',
    ];

    public function render()
    {
        return view('livewire.create-poll');
    }

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function createPoll(){
        $this->validate();
        // if it is not valid, code below will not be executed
        Poll::create([
            'title' => $this->title,
        ])->options()->createMany(
            collect($this->options)->map(function($option){
                return ['name' => $option];
            })->all()
        );
        // foreach($this->options as $optionName){
        //     $poll->options()->create([
        //         'name' => $optionName,
        //     ]);
        // }
        $this->reset(['title', 'options']);
        // 異なるコンポーネント間で通信を行うため
        $this->emit('pollCreated');
    }
}
