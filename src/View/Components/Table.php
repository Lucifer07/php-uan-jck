<?php

namespace PhpuanJck\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $headers;
    public $items;
    public $perPage;
    public $searchable;
    public $pagination;

    public function __construct($headers, $items, $perPage = 20, $searchable = true, $pagination = null)
    {
        $this->headers = $headers;
        $this->items = $items;
        $this->perPage = $perPage;
        $this->searchable = $searchable;
        $this->pagination = $pagination;
    }

    public function render()
    {
        return view('phpuan-jck::components.table');
    }
}
