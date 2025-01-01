<?php

declare(strict_types=1);

namespace TpElections\Model\Repository;

use TpElections\Model\DbConnection;
use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Groupe;
use TpElections\Model\Enum\EtatElectionEnum;

class ElectionRepository
{
    public function findForGroupe(Groupe $groupe): ?Election
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT *
FROM election
WHERE groupe_id = :groupe_id
SQL
        );
        $statement->bindValue(param: ':groupe_id', value: $groupe->id, type: \PDO::PARAM_INT);
        $statement->execute();

        $electionDbData = $statement->fetch();
        if ($electionDbData === false) {
            return null;
        }

        return new Election(
            id: $electionDbData['id'],
            groupe: $groupe,
            date: new \DateTime($electionDbData['date']),
            etat: EtatElectionEnum::{$electionDbData['etat']},
        );
    }

    public function create(Election $election): void
    {
        // L'état initial doit forcément être TOUR_1
        $election->setEtat(EtatElectionEnum::TOUR_1);

        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
INSERT INTO election (groupe_id, date, etat)
VALUES (:groupe_id, :date, :etat)
SQL
        );
        $statement->bindValue(param: ':groupe_id', value: $election->getGroupe()->id, type: \PDO::PARAM_INT);
        $statement->bindValue(param: ':date', value: $election->getDate()->format('Y-m-d H:i:s'));
        $statement->bindValue(param: ':etat', value: $election->getEtat()->name);
        $statement->execute();
    }

    public function saveForNextStep(Election $election): void
    {
        $election->setEtat(
            match ($election->getEtat()) {
                EtatElectionEnum::TOUR_1 => EtatElectionEnum::TOUR_2,
                EtatElectionEnum::TOUR_2 => EtatElectionEnum::CLOTURE,
            }
        );

        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
UPDATE election
SET etat = :etat
WHERE id = :id
SQL
        );
        $statement->bindValue(param: ':etat', value: $election->getEtat()->name);
        $statement->bindValue(param: ':id', value: $election->id, type: \PDO::PARAM_INT);
        $statement->execute();
    }

    public function existsForGroupe(Groupe $groupe): bool
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT COUNT(*)
FROM election
WHERE groupe_id = :groupe_id
SQL
        );
        $statement->bindValue(param: ':groupe_id', value: $groupe->id, type: \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchColumn() > 0;
    }
}
