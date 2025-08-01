<?php

class FormSearchInput extends Component
{
    public $name, $label, $placeholder, $route;

    public function __construct($name, $label = '', $placeholder = '', $route)
    {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->route = $route;
    }

    public function render()
    {
        return view('components.input');
    }
}