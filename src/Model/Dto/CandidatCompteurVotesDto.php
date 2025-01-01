<?php

declare(strict_types=1);

namespace TpElections\Model\Dto;

readonly class CandidatCompteurVotesDto
{
    public function __construct(
        public ?string $candidat,
        public int $compteurVotes,
    ) {
    }
}
