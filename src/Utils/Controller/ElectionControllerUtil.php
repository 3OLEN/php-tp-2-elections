<?php

declare(strict_types=1);

namespace TpElections\Utils\Controller;

use TpElections\Exception\Controller\RequiredElectionForGroupeException;
use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Groupe;
use TpElections\Model\Enum\EtatElectionEnum;
use TpElections\Model\Repository\ElectionRepository;

class ElectionControllerUtil
{
    public static function getElectionForTour1OrRejectAction(): Election
    {
        return static::getElectionForGroupeOrRejectAction(requiredEtat: EtatElectionEnum::TOUR_1);
    }

    public static function getElectionForTour2OrRejectAction(): Election
    {
        return static::getElectionForGroupeOrRejectAction(requiredEtat: EtatElectionEnum::TOUR_2);
    }

    public static function getElectionForClotureOrRejectAction(): Election
    {
        return static::getElectionForGroupeOrRejectAction(requiredEtat: EtatElectionEnum::CLOTURE);
    }

    public static function getElectionForGroupeOrRejectAction(
        ?Groupe $groupe = null,
        ?EtatElectionEnum $requiredEtat = null
    ): Election {
        $groupe ??= GroupControllerUtil::getSelectedGroupOrRejectAction();

        $election = (new ElectionRepository())->findForGroupe($groupe);
        if ($election === null) {
            throw RequiredElectionForGroupeException::createForMissingElection(forGroupe: $groupe);
        }
        if ($requiredEtat !== null && $election->getEtat() !== $requiredEtat) {
            throw RequiredElectionForGroupeException::createForInvalidEtat(
                election: $election,
                requiredEtat: $requiredEtat
            );
        }

        return $election;
    }
}
