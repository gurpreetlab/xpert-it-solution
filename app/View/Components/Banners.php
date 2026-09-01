<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Banners extends Component
{
    public array $banners;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->banners = [
            asset('storage/banners/banner-1.webp'),
            asset('storage/banners/banner-2.webp'),
            asset('storage/banners/banner-3.webp'),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.banners');
    }
}
