<?php

namespace PhpuanJck\View\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    public $label;
    public $severity;

    public function __construct($label, $severity = 'default')
    {
        $this->label = $label;
        $this->severity = $severity;
    }

    public function render()
    {
        return view('phpuan-jck::components.badge');
    }
}
