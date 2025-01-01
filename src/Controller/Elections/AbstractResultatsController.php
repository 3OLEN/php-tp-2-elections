<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections;

use TpElections\Model\Dto\CandidatCompteurVotesDto;
use TpElections\Model\Enum\EtatElectionEnum;
use TpElections\Model\Enum\TourEnum;
use TpElections\Model\Repository\VoteRepository;
use TpElections\Utils\Controller\ElectionControllerUtil;
use TpElections\View\TwigEngine;

abstract class AbstractResultatsController
{
    public function __invoke(): void
    {
        $election = ElectionControllerUtil::getElectionForGroupeOrRejectAction();

        $voteRepository = new VoteRepository();
        if ($voteRepository->hasElectionAllResults(election: $election, tour: $this->getElectionTour()) === false) {
            // Les résultats ne sont pas encore disponibles => redirection vers la page de vote
            $this->redirectToVotePage();
        }

        $votes = $voteRepository->getVotesForElection(election: $election, tour: $this->getElectionTour());

        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: $this->getTemplateForResultats(),
            templateVariables: [
                'page_title' => $this->getTemplatePageTitle(),
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

    abstract protected function getElectionTour(): TourEnum;

    private function getTemplateForResultats(): string
    {
        return match ($this->getElectionTour()) {
            TourEnum::TOUR_1 => 'pages/elections/tour-1/resultats/page.html.twig',
            TourEnum::TOUR_2 => 'pages/elections/resultats/page.html.twig',
        };
    }

    private function getTemplatePageTitle(): string
    {
        return match ($this->getElectionTour()) {
            TourEnum::TOUR_1 => '[Tour 1] Résultats',
            TourEnum::TOUR_2 => 'Résultats élection',
        };
    }

    private function redirectToVotePage(): void
    {
        match ($this->getElectionTour()) {
            TourEnum::TOUR_1 => header('Location: /elections/tour-1'),
            TourEnum::TOUR_2 => header('Location: /elections/tour-2'),
        };
    }
}
