<?php

declare(strict_types=1);

namespace TpElections\Controller\Home;

use TpElections\View\TwigEngine;

class HomeController
{
    public function __invoke(): void
    {
        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/index.html.twig',
            templateVariables: [
                'page_title' => 'Bienvenue sur le site des élections 3OLEN !',
                'group' => '3OLEN-DEV',
            ]
        );
    }
}
