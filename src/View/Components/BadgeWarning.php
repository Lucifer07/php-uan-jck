<?php

namespace PhpuanJck\View\Components;

use Illuminate\View\Component;

class BadgeWarning extends Component
{
    public $label;

    public function __construct($label)
    {
        $this->label = $label;
    }

    public function render()
    {
        return view('phpuan-jck::components.badge-warning');
    }
}
