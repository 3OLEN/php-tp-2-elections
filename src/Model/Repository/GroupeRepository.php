<?php

declare(strict_types=1);

namespace TpElections\Model\Repository;

use TpElections\Model\DbConnection;
use TpElections\Model\Entity\Groupe;

class GroupeRepository
{
    public function find(int $id): ?Groupe
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT *
FROM groupe
WHERE id = :id
SQL
        );
        $statement->bindValue(param: ':id', value: $id, type: \PDO::PARAM_INT);
        $statement->execute();

        $groupeDbData = $statement->fetch();
        if ($groupeDbData === false) {
            return null;
        }

        return new Groupe(
            id: $groupeDbData['id'],
            code: $groupeDbData['code'],
            nom: $groupeDbData['nom'],
        );
    }

    /** @return array<Groupe> */
    public function findAll(): array
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->query(
            query: <<<SQL
SELECT *
FROM groupe
ORDER BY nom
SQL
        );

        $groupes = [];
        foreach ($statement->fetchAll() as $groupeDbData) {
            $groupes[] = new Groupe(
                id: $groupeDbData['id'],
                code: $groupeDbData['code'],
                nom: $groupeDbData['nom'],
            );
        }

        return $groupes;
    }
}
