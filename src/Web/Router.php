<?php

declare(strict_types=1);

namespace TpElections\Web;

use TpElections\Controller\Home\HomeController;
use TpElections\Exception\Web\UnsupportedResourceException;

readonly class Router
{
    public function handleRequest(): void
    {
        $path = parse_url(url: $_SERVER['REQUEST_URI'], component: PHP_URL_PATH);

        // Gestion des assets (« /public »)
        if ($this->isAsset(path: $path)) {
            throw new UnsupportedResourceException();
        }

        // Gestion du Controller
        $controller = match ($path) {
            '/' => new HomeController(),
        };
        // * Utilisation d'un Controller "invokable" (super méthode « __invoke() »)
        $controller();
    }

    private function isAsset(string $path): bool
    {
        return str_starts_with(haystack: $path, needle: '/public/');
    }
}
