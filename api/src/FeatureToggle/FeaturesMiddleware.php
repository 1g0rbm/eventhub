<?php

declare(strict_types=1);

namespace App\FeatureToggle;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FeaturesMiddleware implements MiddlewareInterface
{
    private FeatureSwitch $features;

    private string $header;

    public function __construct(FeatureSwitch $features, string $header = 'X-Features')
    {
        $this->features = $features;
        $this->header   = $header;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header   = $request->getHeaderLine($this->header);
        $features = array_filter(preg_split('/\s*,\s*/', $header));

        foreach ($features as $feature) {
            if (str_starts_with($feature, '!')) {
                $this->features->disable(substr($feature, 1));
            } else {
                $this->features->enable($feature);
            }
        }

        return $handler->handle($request);
    }
}
