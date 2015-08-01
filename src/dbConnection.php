<?php

class ConnectionException extends Exception {

    function __construct($msg) {
        parent::__construct($msg);
    }

}

/* Connect to an ODBC database using driver invocation */

// 3 variables to be defined (in file (with include) or explicitly in this file
//	$user = 'username';		// v13...
//	$dbname = "{$user}_pres;	// {} are needed!
//	$password = 'SecretPassword';

class Connection {

    private static $pdo = null;
    private static $pdoStatement = null;
    private static $user = "";
    private static $password = "";
    private static $dsn = null;

    /**
     * Connect
     * @param String $dbname
     * @param String $user
     * @param String $password
     * @param Sting  $dbms  databasemanagement system 		default: mysql
     * @param String $host	host on which to execute		default: 127.0.1.1  (localhost)
     */
    public function __construct($dbname, $user, $password, $dbms = "mysql", $host = "127.0.0.1") {
        self::$user = $user;
        self::$password = $password;
        self::$dsn = "$dbms:dbname=$dbname;host=$host";
    }

    /**
     * PDO attempts to return the PDO interface 
     * @return PDO object
     */
    public function pdo() {
        if (self::$pdo == null) {
            try {
                self::$pdo = new PDO($this->dsn(), self::$user, self::$password);
            } catch (PDOException $e) {
                $msg = "Connection with database management system failed: " . $this->dsn();
                throw(new ConnectionException($msg)); // or your customized exception
            }
        }
        return $this;
    }

    public function setExceptionToBeReported() {  // Set Errorhandling to Exception
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function unSetExceptionToBeReported() {
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); // or is 0 better?
    }

    private function dsn($v = "") {
        if ($v != "") {
            self::$dsn = $v;
        }
        return self::$dsn;
    }

    public function pdoStatement() {
        return self::$pdoStatement;
    }

    private function prepare($q) {
        self::$pdoStatement = self::$pdo->prepare($q);
    }

    public function execute() {
        self::$pdoStatement->execute();
    }

    public function __call($name, Array $a) {
        switch ($name) {
            case "prepare":
                $this->prepare(implode("", $a));
                return; // leave this switch
            case "bindParam":
                if (isset($a[2])) {
                    self::$pdoStatement->bindParam($a[0], $a[1], $a[2]);
                } else {
                    self::$pdoStatement->bindParam($a[0], $a[1]);
                }
                return;
            // is there a need for bindParams (i.e. array); not implemented
            case "query":
                //print_r($a);
                return self::$pdo->query($a[0], PDO::FETCH_ASSOC); // via magic __call

            case "fetch" :
            case "fetchAll":
                $val = self::$pdoStatement->fetchAll(PDO::FETCH_ASSOC); // PDO::FETCH_OBJ);	// get all and return as array 
                return $val;

            default:
                if (count($a) == 0) {
                    $val = self::$pdoStatement->$name();
                } else {
                    $val = self::$pdoStatement->$name($a);
                }
                return $val;
        }
    }

}
