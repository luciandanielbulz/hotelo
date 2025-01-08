<?php

namespace App\Livewire;

use Livewire\Component;

class TestEmitExample extends Component
{
    public function testEmit()
    {
        logger()->info('TestEmitExample emit aufgerufen');
        $this->emit('testEvent', 'Hello World');
    }

    public function render()
    {
        return view('livewire.test-emit-example');
    }
}
