<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections;

use TpElections\Model\Entity\Election;
use TpElections\Model\Enum\EtatElectionEnum;
use TpElections\Model\Repository\ElectionRepository;
use TpElections\Web\Session\Provider\GroupSessionProvider;

class ElectionsController
{
    public function __invoke(): void
    {
        // Un groupe doit être sélectionné, sinon redirection
        $selectedGroup = GroupSessionProvider::findSelectedGroup();
        if ($selectedGroup === null) {
            header('Location: /');

            return;
        }

        // Récupération de l'élection liée au groupe
        $currentElection = (new ElectionRepository())->findForGroupe(groupe: $selectedGroup);

        // En fonction de l'état de l'élection, on redirige vers la page adéquate
        header("Location: {$this->getRedirectUrlForElection(election: $currentElection)}");
    }

    private function getRedirectUrlForElection(?Election $election): string
    {
        return match (true) {
            $election === null => '/elections/creation',
            $election->getEtat() === EtatElectionEnum::TOUR_1 => '/elections/tour-1',
            $election->getEtat() === EtatElectionEnum::TOUR_2 => '/elections/tour-2',
            $election->getEtat() === EtatElectionEnum::CLOTURE => '/elections/resultats',
        };
    }
}
