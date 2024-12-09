<?php

declare(strict_types=1);

namespace TpElections\Web;

use TpElections\Controller\Error\NotFoundErrorController;
use TpElections\Controller\Home\HomeController;
use TpElections\Exception\Web\NotFoundException;
use TpElections\Exception\Web\UnsupportedResourceException;

readonly class Router
{
    public function handleRequest(): void
    {
        $path = parse_url(url: $_SERVER['REQUEST_URI'], component: PHP_URL_PATH);

        // Gestion des assets (« /public »)
        try {
            if ($this->isAsset(path: $path)) {
                throw new UnsupportedResourceException();
            }
        } catch (NotFoundException $notFoundException) {
            $errorController = new NotFoundErrorController(path: $notFoundException->path);
            $errorController();

            return;
        }

        // Gestion du Controller
        $controller = match ($path) {
            '/' => new HomeController(),
            default => new NotFoundErrorController(path: $path),
        };
        // * Utilisation d'un Controller "invokable" (super méthode « __invoke() »)
        $controller();
    }

    private function isAsset(string $path): bool
    {
        if (str_starts_with(haystack: $path, needle: '/public/') === false) {
            // Ne concerne pas les assets
            return false;
        }

        // 1. Vérification de l'existence du fichier
        if (file_exists(filename: $_SERVER['DOCUMENT_ROOT'] . $path) === false) {
            throw new NotFoundException(path: $path);
        }

        // 2. Vérification du type de fichier (on ne veut pas servir des .php ou des .exe ou des .sh, etc.)
        $mimeType = mime_content_type(filename: $_SERVER['DOCUMENT_ROOT'] . $path);
        $fileExtension = pathinfo(path: $path, flags: PATHINFO_EXTENSION);
        if (
            in_array(
                needle: $mimeType,
                haystack: [
                    'text/plain',
                    'text/css',
                    'application/javascript',
                    'image/png',
                    'image/vnd.microsoft.icon',
                ],
                strict: true
            ) === false
            || in_array(
                needle: $fileExtension,
                haystack: [
                    'css',
                    'js',
                    'png',
                    'ico',
                ],
                strict: true
            ) === false
        ) {
            throw new NotFoundException(path: $path);
        }

        return true;
    }
}
