<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Tour1\Resultats;

use TpElections\Controller\Elections\AbstractResultatsController;
use TpElections\Model\Enum\TourEnum;

class ElectionsTour1ResultatsController extends AbstractResultatsController
{
    protected function getElectionTour(): TourEnum
    {
        return TourEnum::TOUR_1;
    }
}
