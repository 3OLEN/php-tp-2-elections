<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Tour2;

use TpElections\Controller\Elections\AbstractVotePostController;
use TpElections\Model\Enum\TourEnum;

class ElectionsTour2PostController extends AbstractVotePostController
{
    protected function getElectionTour(): TourEnum
    {
        return TourEnum::TOUR_2;
    }
}
