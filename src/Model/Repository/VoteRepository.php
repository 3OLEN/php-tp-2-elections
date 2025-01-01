<?php

declare(strict_types=1);

namespace TpElections\Model\Repository;

use TpElections\Model\DbConnection;
use TpElections\Model\Dto\CandidatCompteurVotesDto;
use TpElections\Model\Entity\Election;
use TpElections\Model\Entity\Individu;
use TpElections\Model\Entity\Vote;
use TpElections\Model\Enum\TourEnum;
use TpElections\Utils\Model\Enum\TourEnumUtil;

class VoteRepository
{
    /** @return array<CandidatCompteurVotesDto> */
    public function getVotesForElection(Election $election, TourEnum $tour): array
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT
    c.id AS candidat_id,
    c.nom AS candidat_nom,
    c.prenom AS candidat_prenom,
    COUNT(v.*) AS compteur_votes
FROM vote v
    LEFT JOIN individu c ON v.candidat_id = c.id
WHERE v.election_id = :election_id
    AND v.tour = :tour
GROUP BY c.id
ORDER BY compteur_votes DESC
SQL
        );
        $statement->bindValue(param: ':election_id', value: $election->id, type: \PDO::PARAM_INT);
        $statement->bindValue(param: ':tour', value: $tour->name);
        $statement->execute();

        $compteursVotes = [];
        foreach ($statement->fetchAll() as $candidatCompteurVotesDbData) {
            $compteursVotes[] = new CandidatCompteurVotesDto(
                candidat: $candidatCompteurVotesDbData['candidat_id'] === null
                    ? null
                    : "{$candidatCompteurVotesDbData['candidat_nom']} {$candidatCompteurVotesDbData['candidat_prenom']}",
                compteurVotes: (int) $candidatCompteurVotesDbData['compteur_votes']
            );
        }

        return $compteursVotes;
    }

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

    public function hasElectionAllResults(Election $election, TourEnum $tour): bool
    {
        $statement = DbConnection::getOrCreateInstance()->pdo->prepare(
            query: <<<SQL
SELECT COUNT(i.*)
FROM individu i
    INNER JOIN groupe g ON i.groupe_id = g.id
    LEFT JOIN vote v ON v.votant_id = i.id AND v.tour = :tour
WHERE g.id = :groupe_id
    AND v.election_id = :election_id
SQL
        );
        $statement->bindValue(param: ':tour', value: $tour->name);
        $statement->bindValue(param: ':groupe_id', value: $election->getGroupe()->id, type: \PDO::PARAM_INT);
        $statement->bindValue(param: ':election_id', value: $election->id, type: \PDO::PARAM_INT);
        $statement->execute();

        return (int) $statement->fetchColumn() === 0;
    }
}
