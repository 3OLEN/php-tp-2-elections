<?php

declare(strict_types=1);

namespace TpElections\Controller\Home;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController
{
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

        // Rendu du template
        echo $twigEngine->render(
            name: 'pages/index.html.twig',
            context: [
                'page_title' => 'Bienvenue sur le site des élections 3OLEN !',
                'group' => '3OLEN-DEV',
            ]
        );
    }
}
