<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Tour1;

use TpElections\Controller\Elections\AbstractVoteController;
use TpElections\Model\Enum\TourEnum;

class ElectionsTour1Controller extends AbstractVoteController
{
    protected function getElectionTour(): TourEnum
    {
        return TourEnum::TOUR_1;
    }
}
