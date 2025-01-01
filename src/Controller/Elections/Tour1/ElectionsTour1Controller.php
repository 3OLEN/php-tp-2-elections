<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Tour1;

use TpElections\Model\Repository\ElectionRepository;
use TpElections\Model\Repository\IndividuRepository;
use TpElections\Utils\Controller\ElectionControllerUtil;
use TpElections\View\TwigEngine;

class ElectionsTour1Controller
{
    public function __invoke(): void
    {
        $election = ElectionControllerUtil::getElectionForTour1OrRejectAction();
        $individuRepository = new IndividuRepository();

        $thoseWhoMustVote = $individuRepository->findThoseWhoMustVoteForElection(election: $election);
        if (count($thoseWhoMustVote) === 0) {
            // Clôture du tour 1 et affichage des résultats
            (new ElectionRepository())->saveForNextStep(election: $election);

            header('Location: /elections/tour-1/resultats');
        }

        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/elections/tour-1/form.html.twig',
            templateVariables: [
                'page_title' => '[Tour 1] Élections',
                'election' => $election,
                'candidats' => $individuRepository->findByGroupe(groupe: $election->getGroupe()),
                'individus_required_vote' => $thoseWhoMustVote,
            ],
        );
    }
}
