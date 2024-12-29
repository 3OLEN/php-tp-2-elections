<?php

declare(strict_types=1);

namespace TpElections\Model\Entity;

readonly class Individu
{
    public function __construct(
        public int $id,
        public string $nom,
        public string $prenom,
        public Groupe $groupe,
    ) {
    }
}
