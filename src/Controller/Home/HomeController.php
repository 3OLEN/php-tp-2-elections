<?php

declare(strict_types=1);

namespace TpElections\Controller\Home;

use TpElections\Model\Repository\ElectionRepository;
use TpElections\Model\Repository\GroupeRepository;
use TpElections\View\TwigEngine;
use TpElections\Web\Session\Provider\GroupSessionProvider;

class HomeController
{
    public function __invoke(): void
    {
        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/index.html.twig',
            templateVariables: [
                'page_title' => 'Bienvenue sur le site des élections 3OLEN !',
                'current_elections' => (new ElectionRepository())->findCurrentElections(),
                'groups' => (new GroupeRepository())->findAll(),
                'selected_group' => GroupSessionProvider::findSelectedGroup(),
            ]
        );
    }
}
