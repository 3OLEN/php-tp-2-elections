CREATE TYPE enum_etat_election AS enum ('TOUR_1', 'TOUR_2', 'CLOTURE');

CREATE TYPE enum_tour AS enum ('TOUR_1', 'TOUR_2');

CREATE TABLE groupe
(
    id serial
        CONSTRAINT groupe_pk
            PRIMARY KEY,
    code varchar(50) NOT NULL
        CONSTRAINT groupe_unique_code
            UNIQUE,
    nom varchar(50) NOT NULL
);

CREATE TABLE individu
(
    id serial
        CONSTRAINT individu_pk
            PRIMARY KEY,
    nom varchar(200) NOT NULL,
    prenom varchar(200) NOT NULL,
    groupe_id integer NOT NULL
        CONSTRAINT individu_groupe_id_fk
            REFERENCES groupe
);

CREATE TABLE election
(
    id serial
        CONSTRAINT election_pk
            PRIMARY KEY,
    groupe_id integer NOT NULL
        CONSTRAINT election_unique_groupe_id
            UNIQUE
        CONSTRAINT election_groupe_id_fk
            REFERENCES groupe,
    date date NOT NULL,
    etat enum_etat_election NOT NULL
);

CREATE TABLE vote
(
    id serial
        CONSTRAINT vote_pk
            PRIMARY KEY,
    election_id integer NOT NULL
        CONSTRAINT vote_votant_individu_id_fk
            REFERENCES individu,
    votant_id integer NOT NULL,
    candidat_id integer
        CONSTRAINT vote_candidat_individu_id_fk
            REFERENCES individu,
    tour enum_tour NOT NULL
);
