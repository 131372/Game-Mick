<?php

$db = array(
    'host' => 'localhost',
    'dbname' => 'Game',
    'user' => 'root',
        // 'pass' => '3Hs8WpT2' 
);

try {
    $db = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'], $db['user']/* , $db['pass'] */);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $db->query("SET SESSION sql_mode = 'ANSI,ONLY_FULL_GROUP_BY'"); 
} catch (PDOException $e) {
    $sMsg = '<p> 
            Regelnummer: ' . $e->getLine() . '<br /> 
            Bestand: ' . $e->getFile() . '<br /> 
            Foutmelding: ' . $e->getMessage() . ' 
        </p>';

    trigger_error($sMsg);
}

function query($query, $par, $db) {

    try {
        $result = $db->prepare($query);
        if ($par != null) {
            foreach ($par as $k => $v) {
                $result->bindValue($k, $v, PDO::PARAM_STR);
            }
        }
        $result->execute();
        if ($result->columnCount() > 0) {
            $endresult = array();
            while ($row = $result->fetch()) {
                array_push($endresult, $row);
            }
            return $endresult;
        }
    } catch (PDOException $e) {
        echo '<pre>';
        echo 'Regel: ' . $e->getLine() . '<br>';
        echo 'Bestand: ' . $e->getFile() . '<br>';
        echo 'Foutmelding: ' . $e->getMessage();
        echo '</pre>';
    }
}

?>
