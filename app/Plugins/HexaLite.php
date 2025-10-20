<?php

namespace App\Plugins;

use Filament\Panel;
use Hexters\HexaLite\HexaLite as BaseHexaLite;

class HexaLite extends BaseHexaLite
{
    public function register(Panel $panel): void
    {
        // Do not register the vendor RoleResource here.
        // App resources are discovered via Panel provider.
    }

    public function boot(Panel $panel): void
    {
        // Keep gates and role definitions from HexaLite,
        // but avoid adding vendor navigation items to prevent duplication.
        $this->registerGates($panel);
        $this->registerGateList($panel);
    }
}

