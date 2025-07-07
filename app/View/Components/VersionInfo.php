<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\VersionService;

class VersionInfo extends Component
{
    public $version;
    public $buildInfo;
    public $showDetailed;

    /**
     * Create a new component instance.
     */
    public function __construct(bool $showDetailed = false)
    {
        $this->showDetailed = $showDetailed;
        $this->version = VersionService::getVersionDisplay();
        $this->buildInfo = VersionService::getBuildInfo();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.version-info');
    }
} 