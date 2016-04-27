<?php
/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 27/04/16
 * Time: 20:53
 */

include_once 'SQLite.php';

$SQLite = new SQLite();

$queries = ['Create Utilisateur' => 'CREATE TABLE `Utilisateur` (
        `id`	INTEGER PRIMARY KEY AUTOINCREMENT,
        `login`	TEXT NOT NULL,
        `password`	TEXT NOT NULL,
        `email`	TEXT NOT NULL,
        `privilege`	INTEGER NOT NULL DEFAULT -1
    );',
];


foreach ($queries as $key => $query) {

    echo 'Start: '.$key.'\n';
    $stmt = $SQLite->prepareQuery($queries);
    $stmt->execute();
    echo 'Finish: '.$key.'\n';
}