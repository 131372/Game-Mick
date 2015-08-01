<?php

/**
 * Description of createTables
 *
 * @author jacob
 */
trait createTables {

    private function setDBTables() {
        $this->createTables();
    }

    private function createTables() {
        $this->createUserTable();
    }

    private function showTables() {
        $q = "
            SELECT name FROM 
                (
                    SELECT * FROM sqlite_master UNION ALL
                    SELECT * FROM sqlite_temp_master
                )
                WHERE type='table'
                ORDER BY name;";
        $r = pdo()->query($q);
        print_r($r->fetchAll());
    }

    private function dropTables() {
        $tables = array("players");

        foreach ($tables as $table) {
            $this->dropTable($table);
        }
    }

    private function dropTable($tableName) {
        pdo()->exec("drop table if exists $tableName;");
    }

    private function createPlayersTable() {
        $this->dropTable("data");
        $q = "
            CREATE TABLE data (
                `data` varchar(1024) CHARACTER SET utf8 NOT NULL
            );
        ";
        pdo()->exec($q);
    }

}
