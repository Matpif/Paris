<?php
/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 27/04/16
 * Time: 20:53
 */

include_once 'SQLite.php';
include_once 'ReadIni.php';

$SQLite = new SQLite();

$queries = ['Create Utilisateur' => 'CREATE TABLE `utilisateur` (
        `id`	INTEGER PRIMARY KEY AUTOINCREMENT,
        `login`	TEXT NOT NULL UNIQUE,
        `firstName`	TEXT,
        `lastName`	TEXT,
        `password`	TEXT NOT NULL,
        `email`	TEXT NOT NULL UNIQUE,
        `privilege`	INTEGER NOT NULL DEFAULT -1
    );',
        'Create Poule' => 'CREATE TABLE `poule` (
        `id`	INTEGER PRIMARY KEY AUTOINCREMENT,
        `name`	TEXT NOT NULL
    );',
        'Create Equipe' => 'CREATE TABLE `equipe` (
        `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        `name`	TEXT,
        `image`	TEXT,
        `poule_id`	INTEGER NOT NULL
    );',
        'Create Match' => 'CREATE TABLE `match` (
        `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        `date`	INTEGER NOT NULL,
        `equipe_id_1`	INTEGER NOT NULL,
        `equipe_id_2`	INTEGER NOT NULL,
        `score_equipe_1`	INTEGER,
        `score_equipe_2`	INTEGER
    )',
        'Create Pari' => 'CREATE TABLE `pari` (
        `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        `utilisateur_id`	INTEGER NOT NULL,
        `match_id`	INTEGER NOT NULL,
        `score_equipe_1`	INTEGER NOT NULL,
        `score_equipe_2`	INTEGER NOT NULL
    )',
        'Create Admin user' => 'INSERT INTO utilisateur
    (login, password, email, privilege)
    values(\'admin\', \'d033e22ae348aeb5660fc2140aec35850c4da997\', \'admin@admin\', 9)',
        'Insert Poules' =>
            'INSERT INTO poule (name)
            values(\'A\')
            , (\'B\')
            , (\'C\')
            , (\'D\')
            , (\'E\')
            , (\'F\')
            ',
        'Insert Equipes' =>
            'INSERT INTO equipe (name, image, poule_id) 
            values(\'France\', \'France.jpg\', 1)
            , (\'Suisse\', \'Suisse.jpg\', 1)
            , (\'Albanie\', \'Albanie.jpg\', 1)
            , (\'Roumanie\', \'Roumanie.jpg\', 1)
            , (\'Angleterre\', \'Angleterre.jpg\', 2)
            , (\'Slovaquie\', \'Slovaquie.jpg\', 2)
            , (\'Pays de Galles\', \'Pays de Galles.jpg\', 2)
            , (\'Russie\', \'Russie.jpg\', 2)
            , (\'Allemagne\', \'Allemagne.jpg\', 3)
            , (\'Irlande N.\', \'Irlande N..jpg\', 3)
            , (\'Ukraine\', \'Ukraine.jpg\', 3)
            , (\'Pologne\', \'Pologne.jpg\', 3)
            , (\'Espagne\', \'Espagne.jpg\', 4)
            , (\'Croatie\', \'Croatie.jpg\', 4)
            , (\'Turquie\', \'Turquie.jpg\', 4)
            , (\'Rép. Tchèque\', \'Rép. Tchèque.jpg\', 4)
            , (\'Belgique\', \'Belgique.jpg\', 5)
            , (\'Italie\', \'Italie.jpg\', 5)
            , (\'Irlande\', \'Irlande.jpg\', 5)
            , (\'Suède\', \'Suède.jpg\', 5)
            , (\'Portugal\', \'Portugal.jpg\', 6)
            , (\'Hongrie\', \'Hongrie.jpg\', 6)
            , (\'Islande\', \'Islande.jpg\', 6)
            , (\'Autriche\', \'Autriche.jpg\', 6)
            ',
        'Insert Matchs' =>
            'INSERT INTO match (date, equipe_id_1, equipe_id_2, score_equipe_1, score_equipe_2)
            values(\'2016-06-10 21:00\', 1, 4, null, null)
            , (\'2016-06-11 15:00\', 3, 2, null, null)
            , (\'2016-06-11 18:00\', 7, 6, null, null)
            , (\'2016-06-11 21:00\', 5, 8, null, null)
            , (\'2016-06-12 15:00\', 15, 14, null, null)
            , (\'2016-06-12 18:00\', 12, 19, null, null)
            , (\'2016-06-12 21:00\', 9, 11, null, null)
            , (\'2016-06-13 15:00\', 13, 16, null, null)
            , (\'2016-06-13 18:00\', 19, 20, null, null)
            , (\'2016-06-13 21:00\', 17, 18, null, null)
            , (\'2016-06-14 18:00\', 24, 22, null, null)
            , (\'2016-06-14 21:00\', 21, 23, null, null)
            , (\'2016-06-15 15:00\', 8, 6, null, null)
            , (\'2016-06-15 18:00\', 4, 2, null, null)
            , (\'2016-06-15 21:00\', 1, 3, null, null)
            , (\'2016-06-16 15:00\', 5, 7, null, null)
            , (\'2016-06-16 18:00\', 11, 19, null, null)
            , (\'2016-06-16 21:00\', 9, 12, null, null)
            , (\'2016-06-17 15:00\', 18, 20, null, null)
            , (\'2016-06-17 18:00\', 16, 14, null, null)
            , (\'2016-06-17 21:00\', 13, 15, null, null)
            , (\'2016-06-18 15:00\', 17, 19, null, null)
            , (\'2016-06-18 18:00\', 23, 22, null, null)
            , (\'2016-06-18 21:00\', 21, 24, null, null)
            , (\'2016-06-19 21:00\', 4, 3, null, null)
            , (\'2016-06-19 21:00\', 2, 1, null, null)
            , (\'2016-06-20 21:00\', 8, 7, null, null)
            , (\'2016-06-20 21:00\', 6, 5, null, null)
            , (\'2016-06-21 18:00\', 11, 12, null, null)
            , (\'2016-06-21 18:00\', 19, 9, null, null)
            , (\'2016-06-21 21:00\', 16, 15, null, null)
            , (\'2016-06-21 21:00\', 14, 13, null, null)
            , (\'2016-06-22 18:00\', 23, 24, null, null)
            , (\'2016-06-22 18:00\', 22, 21, null, null)
            , (\'2016-06-22 21:00\', 18, 19, null, null)
            , (\'2016-06-22 00:00\', 20, 17, null, null)
            ',
];


foreach ($queries as $key => $query) {

    echo 'Start: '.$key."\n";
    $stmt = $SQLite->prepareQuery($query);
    $stmt->execute();
    echo 'Finish: '.$key."\n";
}