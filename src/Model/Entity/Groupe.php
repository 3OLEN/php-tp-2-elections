<?php

declare(strict_types=1);

namespace TpElections\Model\Entity;

readonly class Groupe
{
    public function __construct(
        public int $id,
        public string $code,
        public string $nom,
    ) {
    }
}
