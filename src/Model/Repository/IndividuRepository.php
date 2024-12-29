<?php

declare(strict_types=1);

namespace TpElections\Model\Repository;

use TpElections\Model\DbConnection;
use TpElections\Model\Entity\Groupe;
use TpElections\Model\Entity\Individu;

class IndividuRepository
{
    /** @return array<Individu> */
    public function findByGroupe(Groupe $groupe): array
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT i.*
FROM individu i
    INNER JOIN groupe g ON i.groupe_id = g.id
WHERE g.id = :groupe_id
ORDER BY i.nom, i.prenom
SQL
        );
        $statement->bindValue(param: ':groupe_id', value: $groupe->id, type: \PDO::PARAM_INT);
        $statement->execute();

        $individus = [];
        foreach ($statement->fetchAll() as $individuDbData) {
            $individus[] = new Individu(
                id: $individuDbData['id'],
                nom: $individuDbData['nom'],
                prenom: $individuDbData['prenom'],
                groupe: $groupe,
            );
        }

        return $individus;
    }
}
