<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public $route;
    public $value;

    /**
     * Erstelle die Komponente mit den erforderlichen Props.
     *
     * @param string $route
     * @param string $value
     */
    public function __construct($route = '#', $value = 'Input')
    {
        $this->route = $route;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input');
    }
}
