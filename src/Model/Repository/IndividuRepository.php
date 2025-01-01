<?php

declare(strict_types=1);

namespace TpElections\Model\Repository;

use TpElections\Model\DbConnection;
use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Groupe;
use TpElections\Model\Entity\Individu;
use TpElections\Model\Enum\TourEnum;
use TpElections\Utils\Model\Enum\TourEnumUtil;

class IndividuRepository
{
    public function find(int $id): ?Individu
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT *
FROM individu
WHERE id = :id
SQL
        );
        $statement->bindValue(param: ':id', value: $id, type: \PDO::PARAM_INT);
        $statement->execute();

        $individuDbData = $statement->fetch();
        if ($individuDbData === false) {
            return null;
        }

        return new Individu(
            id: $individuDbData['id'],
            nom: $individuDbData['nom'],
            prenom: $individuDbData['prenom'],
            groupe: (new GroupeRepository())->find(id: $individuDbData['groupe_id']),
        );
    }

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

    public function findThoseWhoMustVoteForElection(Election $election): array
    {
        $voteTour = TourEnumUtil::getTourForElection(election: $election);

        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT i.*
FROM individu i
    INNER JOIN groupe g ON i.groupe_id = g.id
    LEFT JOIN vote v ON i.id = v.votant_id AND v.tour = :tour
WHERE g.id = :groupe_id
    AND v.id IS NULL
ORDER BY i.nom, i.prenom
SQL
        );
        $statement->bindValue(param: ':groupe_id', value: $election->getGroupe()->id, type: \PDO::PARAM_INT);
        $statement->bindValue(param: ':tour', value: $voteTour->name);
        $statement->execute();

        $thoseWhoMustVote = [];
        foreach ($statement->fetchAll() as $individuDbData) {
            $thoseWhoMustVote[] = new Individu(
                id: $individuDbData['id'],
                nom: $individuDbData['nom'],
                prenom: $individuDbData['prenom'],
                groupe: $election->getGroupe(),
            );
        }

        return $thoseWhoMustVote;
    }

    public function findCandidatsForElection(Election $election): array
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT DISTINCT i.*
FROM individu i
    INNER JOIN vote v ON i.id = v.candidat_id AND v.election_id = :election_id AND v.tour = :tour
ORDER BY i.nom, i.prenom
SQL
        );
        $statement->bindValue(param: ':election_id', value: $election->id, type: \PDO::PARAM_INT);
        $statement->bindValue(param: ':tour', value: TourEnum::TOUR_1->name);
        $statement->execute();

        $candidats = [];
        foreach ($statement->fetchAll() as $individuDbData) {
            $candidats[] = new Individu(
                id: $individuDbData['id'],
                nom: $individuDbData['nom'],
                prenom: $individuDbData['prenom'],
                groupe: $election->getGroupe(),
            );
        }

        return $candidats;
    }

    public function countForGroupe(Groupe $groupe): int
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT COUNT(*)
FROM individu
WHERE groupe_id = :groupe_id
SQL
        );
        $statement->bindValue(param: ':groupe_id', value: $groupe->id, type: \PDO::PARAM_INT);
        $statement->execute();

        return (int) $statement->fetchColumn();
    }
}
