<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Tour1\Resultats;

use TpElections\Model\Dto\CandidatCompteurVotesDto;
use TpElections\Model\Enum\EtatElectionEnum;
use TpElections\Model\Enum\TourEnum;
use TpElections\Model\Repository\VoteRepository;
use TpElections\Utils\Controller\ElectionControllerUtil;
use TpElections\View\TwigEngine;

class ElectionsTour1ResultatsController
{
    public function __invoke(): void
    {
        $election = ElectionControllerUtil::getElectionForGroupeOrRejectAction();

        $voteRepository = new VoteRepository();
//        if ($voteRepository->hasElectionAllResults(election: $election, tour: TourEnum::TOUR_1) === false) {
            // Les résultats ne sont pas encore disponibles => redirection vers la page de vote
//            header('Location: /elections/tour-1');
//        }

        $votes = $voteRepository->getVotesForElection(election: $election, tour: TourEnum::TOUR_1);

        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/elections/tour-1/resultats.html.twig',
            templateVariables: [
                'page_title' => '[Tour 1] Résultats',
                'election' => $election,
                'election_tour_2' => EtatElectionEnum::TOUR_2,
                'votes' => $votes,
                'nb_total_votes' => array_reduce(
                    array: $votes,
                    callback: static fn (int $compteur, CandidatCompteurVotesDto $vote): int =>
                        $compteur + $vote->compteurVotes,
                    initial: 0
                ),
            ]
        );
    }
}
