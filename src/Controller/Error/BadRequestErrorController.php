<?php

declare(strict_types=1);

namespace TpElections\Controller\Error;

use TpElections\View\TwigEngine;

class BadRequestErrorController
{
    public function __construct(
        private readonly string $message,
    ) {
    }

    public function __invoke(): void
    {
        http_response_code(400);

        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/errors/400.html.twig',
            templateVariables: [
                'error_message' => $this->message,
            ]
        );
    }
}
