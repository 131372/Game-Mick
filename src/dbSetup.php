<?php

// inside a function/method pdo()-> can be used.
// dus pdo()->prepare("..."); om een query kenbaar te maken. zie ook PDO in php.net
$dbh = null;
function pdo() {
    global $dbh;
    if ($dbh == null) {
        include "dbInit.php"; 
        
        $dbh = new Connection($dbName, $dbUser, $dbWachtwoord);            
    }
    return $dbh->pdo();
}

// probeer verbinding op te zetten. als het mislukt een error melding.
// dit is niet de Exception die je krijgt als een query niet goed is. De verbinging 
// met database management is er dan al.
try {    
    pdo()->setExceptionToBeReported(); // tijdens ontwikkelen zoveel mogelijk boodschappen met fouten op de database
    // kijk ook in .htaccess ; zet daar de php error reporting uit.
    //pdo()->unSetExceptionToBeReported();
} catch (ConnectionException $e) {
    print "Exception: " . $e->getMessage();
    die();
}


