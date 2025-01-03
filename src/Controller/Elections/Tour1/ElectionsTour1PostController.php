<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Tour1;

use TpElections\Controller\Elections\AbstractVotePostController;
use TpElections\Model\Enum\TourEnum;

class ElectionsTour1PostController extends AbstractVotePostController
{
    protected function getElectionTour(): TourEnum
    {
        return TourEnum::TOUR_1;
    }
}
