<?php

declare(strict_types=1);

namespace TpElections\Model\Repository;

use TpElections\Model\DbConnection;
use TpElections\Model\Entity\Groupe;

class GroupeRepository
{
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
