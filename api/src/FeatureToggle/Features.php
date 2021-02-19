<?php

declare(strict_types=1);

namespace App\FeatureToggle;

class Features implements FeatureFlag, FeatureSwitch
{
    /**
     * @param bool[] $features
     *
     * @psalm-param array<string, bool>
     */
    private array $features;

    /**
     * @param bool[] $features
     *
     * @psalm-param array<string, bool>
     */
    public function __construct(array $features)
    {
        $this->features = $features;
    }

    public function isEnabled(string $name): bool
    {
        if (!array_key_exists($name, $this->features)) {
            return false;
        }

        return $this->features[$name];
    }

    public function enabled(string $name): void
    {
        $this->features[$name] = true;
    }
}
