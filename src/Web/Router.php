<?php

declare(strict_types=1);

namespace TpElections\Web;

use TpElections\Controller\Error\NotFoundErrorController;
use TpElections\Controller\Home\HomeController;
use TpElections\Exception\Web\NotFoundException;
use TpElections\Exception\Web\UnsupportedResourceException;
use TpElections\Utils\File\ApplicationFileUtil;

readonly class Router
{
    public function handleRequest(): void
    {
        $path = parse_url(url: $_SERVER['REQUEST_URI'], component: PHP_URL_PATH);

        // Gestion des assets (« /public »)
        try {
            if (ApplicationFileUtil::isAsset(path: $path)) {
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
}
