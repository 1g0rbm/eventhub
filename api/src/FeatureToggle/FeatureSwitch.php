<?php

declare(strict_types=1);

namespace App\FeatureToggle;

interface FeatureSwitch
{
    public function enabled(string $name): void;
}
