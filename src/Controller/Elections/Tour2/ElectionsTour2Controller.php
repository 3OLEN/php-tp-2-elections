<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Tour2;

use TpElections\Controller\Elections\AbstractVoteController;
use TpElections\Model\Enum\TourEnum;

class ElectionsTour2Controller extends AbstractVoteController
{
    protected function getElectionTour(): TourEnum
    {
        return TourEnum::TOUR_2;
    }
}
