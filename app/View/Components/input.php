<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $label;
    public $type;
    public $name;
    public $value;
    public $placeholder;
    public $required;

    public function __construct(
        $label = '',
        $type = 'text',
        $name,
        $value = '',
        $placeholder = '',
        $required = false
    ) {
        $this->label = $label;
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    public function render()
    {
        return view('components.input');
    }
}