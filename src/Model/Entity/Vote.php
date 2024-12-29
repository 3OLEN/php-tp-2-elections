<?php

declare(strict_types=1);

namespace TpElections\Model\Entity;

use TpElections\Model\Enum\TourEnum;

class Vote
{
    public function __construct(
        public readonly ?int $id = null,
        private ?Election $election = null,
        private ?Individu $votant = null,
        private ?Individu $candidat = null,
        private ?TourEnum $tour = null,
    ) {
    }

    public function getElection(): ?Election
    {
        return $this->election;
    }

    public function setElection(Election $election): static
    {
        $this->election = $election;

        return $this;
    }

    public function getVotant(): ?Individu
    {
        return $this->votant;
    }

    public function setVotant(Individu $votant): static
    {
        $this->votant = $votant;

        return $this;
    }

    public function getCandidat(): ?Individu
    {
        return $this->candidat;
    }

    public function setCandidat(?Individu $candidat): static
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getTour(): ?TourEnum
    {
        return $this->tour;
    }

    public function setTour(TourEnum $tour): static
    {
        $this->tour = $tour;

        return $this;
    }
}
