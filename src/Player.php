<?php

/**
 * contains information of a player
 */
class Player {

    public $nr;
    public $id; // id of the player
    public $name;   // name given by the player
    public $sha;    // unique id
    public $joinTime;   // when registered
    public $gameId;     // what game 

    public function __construct() {
        
    }
    public function setNr($nr) {
        $this->nr = $nr;
    }
    function __get($name) {
        return $this->$name;
    }

    public function set($data) {
        foreach ($data AS $key => $value)
            $this->{$key} = $value;
    }

    public function init($name) {
        $this->name = $name;
        $this->id = uniqid("id");
        $this->sha = sha1($this->id);
        $this->joinTime = time();
        $this->gameId = '';
    }

    public function getGameId() {
        return $this->gameId;
    }

    public function setGameId($gameId) {
        $this->gameId = $gameId;
    }

    public function toPrint() {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }

    public function toString() {
        return "Player " . $this->name;
    }

}
