<?php

declare(strict_types=1);

namespace TpElections\Model\Repository;

use TpElections\Model\DbConnection;
use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Groupe;
use TpElections\Model\Enum\EtatElectionEnum;

class ElectionRepository
{
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
}
