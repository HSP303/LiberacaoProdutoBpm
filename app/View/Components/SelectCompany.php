<?php

namespace App\View\Components;

use Closure;
use Domain\Senior\Services\SeniorCompanyBranchService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Component;

class SelectCompany extends Component
{

    public array $branches;
    public ?string $branchSelected;

    /**
     * Create a new component instance.
     * @throws ValidationException
     */
    public function __construct()
    {
        $this->branches = [];//(new SeniorCompanyBranchService())->list(Auth::user());
        $this->branchSelected = Session::get('branch');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-company');
    }
}
