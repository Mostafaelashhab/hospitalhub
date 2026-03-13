<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public bool $wide;

    public function __construct(bool $wide = false)
    {
        $this->wide = $wide;
    }

    public function render(): View
    {
        return view('layouts.guest');
    }
}
