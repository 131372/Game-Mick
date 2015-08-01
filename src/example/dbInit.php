<?php

if (LOCAL) {
    $dbUser = 'root';
} else {
    $dbUser = 'v14...';
}
$dbPrefix = $dbUser . '_';
$dbPrefix = '';
$dbName = $dbPrefix . 'GameSetup';   // aparte database
$dbWachtwoord = '';
