<?php

declare(strict_types=1);

namespace TpElections\Web;

use TpElections\Controller\{
    Elections\Creation\ElectionsCreationController,
    Elections\Creation\ElectionsCreationPostController,
    Elections\ElectionsController,
    Elections\Resultats\ElectionsResultatsController,
    Elections\Tour1\ElectionsTour1Controller,
    Elections\Tour1\ElectionsTour1PostController,
    Elections\Tour1\Resultats\ElectionsTour1ResultatsController,
    Elections\Tour2\ElectionsTour2Controller,
    Elections\Tour2\ElectionsTour2PostController,
    Error\NotFoundErrorController,
    Groupes\Selection\SelectionGroupeController,
    Home\HomeController
};
use TpElections\Exception\{
    Controller\RequiredElectionForGroupeException,
    Controller\RequiredSelectedGroupException,
    Web\NotFoundException,
    Web\UnsupportedResourceException
};
use TpElections\Utils\{
    File\ApplicationFileUtil,
    Router\PathUtil
};
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
            '/elections/resultats' => new ElectionsResultatsController(),
            '/elections/tour-1' => $requestMethod === 'POST'
                ? new ElectionsTour1PostController()
                : new ElectionsTour1Controller(),
            '/elections/tour-1/resultats' => new ElectionsTour1ResultatsController(),
            '/elections/tour-2' => $requestMethod === 'POST'
                ? new ElectionsTour2PostController()
                : new ElectionsTour2Controller(),
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
            // L'action nécessite de sélectionner le groupe => redirection accueil
            MessageSessionProvider::defineMessageForNextAction(
                message: $requiredSelectedGroupException->getMessage(),
            );
            header('Location: /');
        } catch (RequiredElectionForGroupeException $requiredElectionForGroupeException) {
            // L'action nécessite une élection pour le groupe ou d'avoir la bonne étape => redirection accueil
            MessageSessionProvider::defineMessageForNextAction(
                message: $requiredElectionForGroupeException->getMessage(),
            );
            header('Location: /');
        }
    }
}
