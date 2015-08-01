<?php

function pdo() {
    global $dbh;
    if ($dbh == null) {
        if (LOCAL) {
            $dbUser = 'root';
        } else {
            $dbUser = 'jbunscho';
        }
        $dbPrefix = 'jbunscho_';
        $dbName = $dbPrefix . 'mplayer';
        $dbWachtwoord = '';
        $db = "'sqlite::memory:'";
        $dbh = new Connection($dbName, $dbUser, $dbWachtwoord, $db);
    }
    return $dbh->pdo();
}
