<?php

namespace PhpuanJck\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $currentRoute;

    public function __construct($currentRoute = null)
    {
        $this->currentRoute = $currentRoute;
    }

    public function render()
    {
        return view('phpuan-jck::components.sidebar');
    }
}
