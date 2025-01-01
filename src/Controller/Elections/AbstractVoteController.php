<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections;

use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Individu;
use TpElections\Model\Enum\TourEnum;
use TpElections\Model\Repository\ElectionRepository;
use TpElections\Model\Repository\IndividuRepository;
use TpElections\Utils\Controller\ElectionControllerUtil;
use TpElections\View\TwigEngine;

abstract class AbstractVoteController
{
    private IndividuRepository $individuRepository;

    public function __invoke(): void
    {
        $election = $this->getElectionForTour();
        $this->individuRepository = new IndividuRepository();

        $thoseWhoMustVote = $this->individuRepository->findThoseWhoMustVoteForElection(election: $election);
        if (count($thoseWhoMustVote) === 0) {
            // Clôture du tour et affichage des résultats
            (new ElectionRepository())->saveForNextStep(election: $election);

            $this->redirectToResultPage();
        }

        TwigEngine::getOrCreateInstance()->renderTemplate(
            templateLocation: 'pages/elections/vote-form.html.twig',
            templateVariables: [
                'page_title' => $this->getTemplatePageTitle(),
                'election' => $election,
                'candidats' => $this->getCandidatList(election: $election),
                'individus_required_vote' => $thoseWhoMustVote,
            ],
        );
    }

    abstract protected function getElectionTour(): TourEnum;

    private function getElectionForTour(): Election {
        return match ($this->getElectionTour()) {
            TourEnum::TOUR_1 => ElectionControllerUtil::getElectionForTour1OrRejectAction(),
            TourEnum::TOUR_2 => ElectionControllerUtil::getElectionForTour2OrRejectAction(),
        };
    }

    private function getTemplatePageTitle(): string
    {
        return match ($this->getElectionTour()) {
            TourEnum::TOUR_1 => '[Tour 1] Élections',
            TourEnum::TOUR_2 => '[Tour 2] Élections',
        };
    }

    /** @return array<Individu> */
    private function getCandidatList(Election $election): array
    {
        return match ($this->getElectionTour()) {
            TourEnum::TOUR_1 => $this->individuRepository->findByGroupe(groupe: $election->getGroupe()),
            TourEnum::TOUR_2 => $this->individuRepository->findCandidatsForElection(
                election: $election,
            ),
        };
    }

    private function redirectToResultPage(): void
    {
        match ($this->getElectionTour()) {
            TourEnum::TOUR_1 => header('Location: /elections/tour-1/resultats'),
            TourEnum::TOUR_2 => header('Location: /elections/resultats'),
        };
    }
}
