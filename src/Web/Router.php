<?php

declare(strict_types=1);

namespace TpElections\Web;

use TpElections\Controller\Elections\Creation\ElectionsCreationController;
use TpElections\Controller\Elections\Creation\ElectionsCreationPostController;
use TpElections\Controller\Elections\ElectionsController;
use TpElections\Controller\Error\NotFoundErrorController;
use TpElections\Controller\Groupes\Selection\SelectionGroupeController;
use TpElections\Controller\Home\HomeController;
use TpElections\Exception\Controller\RequiredSelectedGroupException;
use TpElections\Exception\Web\NotFoundException;
use TpElections\Exception\Web\UnsupportedResourceException;
use TpElections\Utils\File\ApplicationFileUtil;
use TpElections\Utils\Router\PathUtil;
use TpElections\Web\Session\Provider\MessageSessionProvider;

readonly class Router
{
    public function handleRequest(): void
    {
        $path = PathUtil::getRequestPath();

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
        $controller = $this->getController(
            requestPath: $path,
            requestMethod: $_SERVER['REQUEST_METHOD'],
        );
        // * Utilisation d'un Controller "invokable" (super méthode « __invoke() »)
        $this->invokeController(controller: $controller);
    }

    private function getController(string $requestPath, string $requestMethod): object
    {
        return match ($requestPath) {
            '/' => new HomeController(),
            '/elections' => new ElectionsController(),
            '/elections/creation' => $requestMethod === 'POST'
                ? new ElectionsCreationPostController()
                : new ElectionsCreationController(),
            '/groupes/selection' => $requestMethod === 'POST'
                ? new SelectionGroupeController()
                : null,
            default => null,
        }
            ?? new NotFoundErrorController(path: $requestPath);
    }

    private function invokeController(callable $controller): void
    {
        try {
            $controller();
        } catch (RequiredSelectedGroupException $requiredSelectedGroupException) {
            // L'action nécessite de sélectionner le groupe => redirection vers page d'accueil
            MessageSessionProvider::defineMessageForNextAction(
                message: $requiredSelectedGroupException->getMessage(),
            );
            header('Location: /');
        }
    }
}
