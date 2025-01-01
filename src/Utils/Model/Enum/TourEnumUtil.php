<?php

declare(strict_types=1);

namespace TpElections\Utils\Model\Enum;

use TpElections\Model\Entity\Election;
use TpElections\Model\Enum\EtatElectionEnum;
use TpElections\Model\Enum\TourEnum;

class TourEnumUtil
{
    public static function getTourForElection(Election $election): TourEnum
    {
        return match ($election->getEtat()) {
            EtatElectionEnum::TOUR_1 => TourEnum::TOUR_1,
            EtatElectionEnum::TOUR_2 => TourEnum::TOUR_2,
        };
    }
}
