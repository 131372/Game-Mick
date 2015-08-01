<?php

// initial setup to talk with mySql database
// definitions
include_once "dbInit.php"; // external defined: $dbUser = '....' , $dbName = "{$user}_...." and $dbWachtwoord= ...;
// code to talk to database manager
include_once "dbConnection.php"; // class handling the setting of the PDO object




class dbInterface {

    private $keepDB;

    function __construct($keepDB = true) {
        $this->keepDB = $keepDB;
    }
    function setKeepDB($value) {
        $this->keepDB = $value;
    }

    function write(Content $o) {
        if ($this->keepDB == false) {
            $this->truncate();
            $this->keepDB = true;
        }
        foreach ($o->players as $player) {
            $str = json_encode($player);
            $q = "insert into data values ('$str')";
            pdo()->prepare($q);
            pdo()->execute(array());
        }
    }

    function truncate() {
        $q = "truncate table data";        
        pdo()->query($q);
    }

    function read() {
        $q = 'select * from data';
        pdo()->prepare($q);
        pdo()->execute();
        $players = array();
        $r = pdo()->fetchAll(array());
        foreach($r as $player) {            
            $players[] =  json_decode($player['data'], true);
        }
        
        return $players;
    }

}
