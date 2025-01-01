<?php

declare(strict_types=1);

namespace TpElections\Controller\Groupes\Selection;

use TpElections\Controller\Error\BadRequestErrorController;
use TpElections\Model\Repository\GroupeRepository;
use TpElections\Web\Session\Provider\GroupSessionProvider;

class SelectionGroupeController
{
    public function __invoke(): void
    {
        $selectedGroupId = filter_var(
            value: $_POST['group'] ?? null,
            filter: FILTER_VALIDATE_INT,
            options: ['min_range' => 1],
        );
        if ($selectedGroupId === false) {
            $errorController = new BadRequestErrorController(
                message: 'Valeur "group" de la requête POST est invalide.',
            );
            $errorController(); // __invoke() toujours

            return;
        }

        // Récupération du groupe
        $selectedGroup = (new GroupeRepository())->find(id: $selectedGroupId);
        if ($selectedGroup === null) {
            $errorController = new BadRequestErrorController(
                message: "Le groupe d'id #{$selectedGroupId} n'existe pas.",
            );
            $errorController(); // __invoke() toujours

            return;
        }

        // Enregistrement du groupe sélectionné en session
        GroupSessionProvider::setSelectedGroup(group: $selectedGroup);

        // Redirection vers la page des élections de ce groupe
        header('Location: /elections');
    }
}
