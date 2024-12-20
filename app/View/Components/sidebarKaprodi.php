<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class sidebarKaprodi extends Component
{
    /**
     * Create a new component instance.
     */
    public $kaprodi;
    public function __construct($kaprodi)
    {
        $this->kaprodi = $kaprodi;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar-kaprodi');
    }
}
