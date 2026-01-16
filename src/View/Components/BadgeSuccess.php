<?php

namespace PhpuanJck\View\Components;

use Illuminate\View\Component;

class BadgeSuccess extends Component
{
    public $label;

    public function __construct($label)
    {
        $this->label = $label;
    }

    public function render()
    {
        return view('phpuan-jck::components.badge-success');
    }
}
