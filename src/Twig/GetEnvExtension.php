<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GetEnvExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getenv', [$this, 'getenv'])
        ];
    }

    public function getenv(string $name): ?string
    {
        return $_ENV[$name] ?? null;
    }
}
