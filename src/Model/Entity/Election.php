<?php

declare(strict_types=1);

namespace TpElections\Model\Entity;

use TpElections\Model\Enum\EtatElectionEnum;

class Election
{
    public function __construct(
        public readonly ?int $id = null,
        private ?Groupe $groupe = null,
        private ?\DateTime $date = null,
        private ?EtatElectionEnum $etat = null,
    ) {
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(Groupe $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?EtatElectionEnum
    {
        return $this->etat;
    }

    public function setEtat(EtatElectionEnum $etat): static
    {
        $this->etat = $etat;

        return $this;
    }
}
