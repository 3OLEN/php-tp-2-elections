<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Creation;

use TpElections\Controller\Error\BadRequestErrorController;
use TpElections\Model\Entity\Election;
use TpElections\Model\Repository\ElectionRepository;
use TpElections\Utils\Controller\GroupControllerUtil;

class ElectionsCreationPostController
{
    public function __invoke(): void
    {
        // Un groupe doit être sélectionné
        $selectedGroup = GroupControllerUtil::getSelectedGroupOrRejectAction(actionName: 'Création d\'élection');

        $electionRepository = new ElectionRepository();

        // Vérification de l'existence d'une élection pour le groupe
        if ($electionRepository->existsForGroupe(groupe: $selectedGroup)) {
            $errorController = new BadRequestErrorController(
                message: "Une élection existe déjà pour le groupe {$selectedGroup->nom}."
            );
            $errorController(); // __invoke() toujours

            return;
        }

        // Création de l'entité pour enregistrement
        $election = new Election(
            groupe: $selectedGroup,
            date: new \DateTime(),
        );
        $electionRepository->create(election: $election);

        // Redirection vers la page du premier tour
        header('Location: /elections/tour-1');
    }
}
