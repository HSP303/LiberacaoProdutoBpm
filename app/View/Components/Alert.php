<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public function __construct(
        public ?string $title = 'Sucesso!',
        public string $icon = 'success',
    ) {}

    public function render()
    {
        return view('components.alert');
    }
}