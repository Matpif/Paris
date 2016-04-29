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
        `login`	TEXT NOT NULL,
        `password`	TEXT NOT NULL,
        `email`	TEXT NOT NULL,
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
    values(\'admin\', \'d033e22ae348aeb5660fc2140aec35850c4da997\', \'admin@admin\', 9);',
];


foreach ($queries as $key => $query) {

    echo 'Start: '.$key."\n";
    $stmt = $SQLite->prepareQuery($query);
    $stmt->execute();
    echo 'Finish: '.$key."\n";
}