<?php

declare(strict_types=1);

namespace TpElections\Controller\Home;

use TpElections\Model\Enum\EtatElectionEnum;
use TpElections\Model\Repository\ElectionRepository;
use TpElections\Model\Repository\GroupeRepository;
use TpElections\View\TwigEngine;
use TpElections\Web\Session\Provider\GroupSessionProvider;

class HomeController
{
    public function __invoke(): void
    {
        $selectedGroup = GroupSessionProvider::findSelectedGroup();

        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/index.html.twig',
            templateVariables: [
                'page_title' => 'Bienvenue sur le site des élections 3OLEN !',
                'groupes' => (new GroupeRepository())->findAll(),
                'selected_group' => $selectedGroup,
                'related_group_election' => $selectedGroup === null
                    ? null
                    : (new ElectionRepository())->findForGroupe($selectedGroup),
                'etat_election_tour_1' => EtatElectionEnum::TOUR_1,
                'etat_election_tour_2' => EtatElectionEnum::TOUR_2,
            ]
        );
    }
}
