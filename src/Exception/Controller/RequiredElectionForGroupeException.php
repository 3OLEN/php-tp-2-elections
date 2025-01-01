<?php

declare(strict_types=1);

namespace TpElections\Exception\Controller;

use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Groupe;
use TpElections\Model\Enum\EtatElectionEnum;

class RequiredElectionForGroupeException extends \Exception
{
    public static function createForMissingElection(Groupe $forGroupe): static
    {
        return new static(
            message: "Aucune élection n'a été trouvée pour le groupe « {$forGroupe->nom} »."
        );
    }

    public static function createForInvalidEtat(Election $election, EtatElectionEnum $requiredEtat): static
    {
        return new static(
            message: <<<MSG
L'action ne peut se poursuivre car l'élection du groupe « {$election->getGroupe()->nom} » est à l'état
« {$election->getEtat()->name} » alors qu'elle devrait être à l'état « {$requiredEtat->name} » pour cette action.
MSG
        );
    }

    private function __construct(string $message)
    {
        parent::__construct(
            message: $message,
            code: 404
        );
    }
}
