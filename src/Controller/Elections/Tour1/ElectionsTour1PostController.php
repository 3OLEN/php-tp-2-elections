<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Tour1;

use TpElections\Controller\Error\BadRequestErrorController;
use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Individu;
use TpElections\Model\Entity\Vote;
use TpElections\Model\Enum\TourEnum;
use TpElections\Model\Repository\IndividuRepository;
use TpElections\Model\Repository\VoteRepository;
use TpElections\Utils\Controller\ElectionControllerUtil;

class ElectionsTour1PostController
{
    private IndividuRepository $individuRepository;

    public function __invoke(): void
    {
        $election = ElectionControllerUtil::getElectionForTour1OrRejectAction();

        $this->individuRepository = new IndividuRepository();
        // Récupération des individus
        $votant = $this->getVotantForElection(election: $election);
        $candidat = $this->getCandidatForElection(election: $election);

        $voteRepository = new VoteRepository();
        // Vérification que le votant n'a pas déjà voté
        if ($voteRepository->existsForVotantAndElection(votant: $votant, election: $election)) {
            $errorController = new BadRequestErrorController(
                message: "L'individu votant d'id #{$votant->id} a déjà voté pour cette élection.",
            );
            $errorController(); // __invoke() toujours

            return;
        }

        // Enregistrement du vote
        $vote = new Vote(
            election: $election,
            votant: $votant,
            candidat: $candidat,
            tour: TourEnum::TOUR_1,
        );
        $voteRepository->create($vote);

        // Redirection vers la page du tour 1 des élections
        header('Location: /elections/tour-1');
    }

    private function getVotantForElection(Election $election): Individu
    {
        $votantId = filter_var(
            value: $_POST['votant'] ?? null,
            filter: FILTER_VALIDATE_INT,
            options: ['min_range' => 1],
        );
        if ($votantId === false) {
            $errorController = new BadRequestErrorController(
                message: 'Valeur "votant" de la requête POST est invalide.',
            );
            $errorController(); // __invoke() toujours

            exit;
        }

        return $this->getIndividuForElection(individuId: $votantId, individuType: 'votant', election: $election);
    }

    private function getCandidatForElection(Election $election): ?Individu
    {
        if (array_key_exists('candidat', $_POST) && $_POST['candidat'] === '') {
            return null;
        }

        $candidatId = filter_var(
            value: $_POST['candidat'] ?? null,
            filter: FILTER_VALIDATE_INT,
            options: ['min_range' => 1],
        );
        if ($candidatId === false) {
            $errorController = new BadRequestErrorController(
                message: 'Valeur "candidat" de la requête POST est invalide.',
            );
            $errorController(); // __invoke() toujours

            exit;
        }

        return $this->getIndividuForElection(individuId: $candidatId, individuType: 'candidat', election: $election);
    }

    private function getIndividuForElection(int $individuId, string $individuType, Election $election): Individu
    {
        $individu = $this->individuRepository->find(id: $individuId);
        if ($individu === null || $individu->groupe->id !== $election->getGroupe()->id) {
            $errorController = new BadRequestErrorController(
                message: <<<MSG
L'individu {$individuType} d'id #{$individuId} n'existe pas ou n'appartient pas au groupe de l'élection.
MSG
            );
            $errorController(); // __invoke() toujours

            exit;
        }

        return $individu;
    }
}
