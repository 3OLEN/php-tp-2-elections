<?php

declare(strict_types=1);

namespace TpElections\Controller\Error;

use TpElections\View\TwigEngine;

class NotFoundErrorController
{
    public function __construct(
        private readonly string $path,
    ) {
    }

    public function __invoke(): void
    {
        http_response_code(404);

        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/errors/404.html.twig',
            templateVariables: [
                'resource_value' => $this->path,
            ]
        );
    }
}
