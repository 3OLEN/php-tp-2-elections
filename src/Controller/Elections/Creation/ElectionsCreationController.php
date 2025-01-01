<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Creation;

use TpElections\Model\Repository\IndividuRepository;
use TpElections\Utils\Controller\GroupControllerUtil;
use TpElections\View\TwigEngine;

class ElectionsCreationController
{
    public function __invoke(): void
    {
        // Un groupe doit être sélectionné
        $selectedGroup = GroupControllerUtil::getSelectedGroupOrRejectAction();

        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/elections/creation/form.html.twig',
            templateVariables: [
                'page_title' => 'Création de l\'élection',
                'groupe' => $selectedGroup,
                'groupe_count' => (new IndividuRepository())->countForGroupe(groupe: $selectedGroup),
            ]
        );
    }
}
