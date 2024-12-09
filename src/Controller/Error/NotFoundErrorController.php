<?php

declare(strict_types=1);

namespace TpElections\Controller\Error;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

readonly class NotFoundErrorController
{
    public function __construct(
        private string $path,
    ) {
    }

    public function __invoke(): void
    {
        // View avec Twig
        $twigTemplateLoader = new FilesystemLoader(paths: $_SERVER['DOCUMENT_ROOT'] . '/templates');
        $twigEngine = new Environment(
            loader: $twigTemplateLoader,
            options: [
                'strict_variables' => true,
            ],
        );
        $twigEngine->addGlobal(name: 'session_id', value: session_id());

        http_response_code(404);

        // Rendu du template
        echo $twigEngine->render(
            name: 'pages/errors/404.html.twig',
            context: [
                'resource_value' => $this->path,
            ]
        );
    }
}
