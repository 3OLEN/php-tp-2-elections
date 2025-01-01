<?php

declare(strict_types=1);

namespace TpElections\Controller\Elections\Resultats;

use TpElections\Controller\Elections\AbstractResultatsController;
use TpElections\Model\Enum\TourEnum;

class ElectionsResultatsController extends AbstractResultatsController
{
    protected function getElectionTour(): TourEnum
    {
        return TourEnum::TOUR_2;
    }
}
