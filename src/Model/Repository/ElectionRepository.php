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

    public function findCurrentElections(): array
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT
      e.id
    , e.date
    , e.etat
    , g.id AS groupe_id
    , g.code AS groupe_code
    , g.nom AS groupe_nom
FROM election e
    INNER JOIN groupe g ON e.groupe_id = g.id
WHERE e.etat <> :closed_state
ORDER BY e.date
SQL
        );
        $statement->bindValue(param: ':closed_state', value: EtatElectionEnum::CLOTURE->name);
        $statement->execute();

        $currentElections = [];
        foreach ($statement->fetchAll() as $electionDbData) {
            $currentElections[] = new Election(
                id: $electionDbData['id'],
                groupe: new Groupe(
                    id: $electionDbData['groupe_id'],
                    code: $electionDbData['groupe_code'],
                    nom: $electionDbData['groupe_nom'],
                ),
                date: new \DateTime($electionDbData['date']),
                etat: EtatElectionEnum::{$electionDbData['etat']},
            );
        }

        return $currentElections;
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
