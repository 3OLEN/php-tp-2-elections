<?php

declare(strict_types=1);

namespace TpElections\Model\Repository;

use TpElections\Model\DbConnection;
use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Individu;
use TpElections\Model\Entity\Vote;
use TpElections\Utils\Model\Enum\TourEnumUtil;

class VoteRepository
{
    public function create(Vote $vote): void
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
INSERT INTO vote (election_id, votant_id, candidat_id, tour)
VALUES (:election_id, :votant_id, :candidat_id, :tour)
SQL
        );
        $statement->bindValue(param: ':election_id', value: $vote->getElection()->id, type: \PDO::PARAM_INT);
        $statement->bindValue(param: ':votant_id', value: $vote->getVotant()->id, type: \PDO::PARAM_INT);
        if ($vote->getCandidat() === null) {
            $statement->bindValue(param: ':candidat_id', value: null, type: \PDO::PARAM_NULL);
        } else {
            $statement->bindValue(param: ':candidat_id', value: $vote->getCandidat()->id, type: \PDO::PARAM_INT);
        }
        $statement->bindValue(param: ':tour', value: $vote->getTour()->name);
        $statement->execute();
    }

    public function existsForVotantAndElection(Individu $votant, Election $election): bool
    {
        $voteTour = TourEnumUtil::getTourForElection(election: $election);

        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT COUNT(v.*)
FROM vote v
    INNER JOIN election e ON v.election_id = e.id
WHERE v.votant_id = :votant_id
    AND e.id = :election_id
    AND v.tour = :tour
SQL
        );
        $statement->bindValue(param: ':votant_id', value: $votant->id, type: \PDO::PARAM_INT);
        $statement->bindValue(param: ':election_id', value: $election->id, type: \PDO::PARAM_INT);
        $statement->bindValue(param: ':tour', value: $voteTour->name);
        $statement->execute();

        return $statement->fetchColumn() > 0;
    }
}
