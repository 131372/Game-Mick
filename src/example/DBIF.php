<?php

include_once './dbInit.php';
include_once '../dbConnection.php';

class DBIF {
    private $gameId;
    function __construct() {
        
    }
    public function setGameId($gameId) {
        $this->gameId = $gameId;
        // also put in database
    }
    public function getGameId() {
        // return the current GameId
    }
    /**
     * 
     * @param type $player
     * @param type $naam
     */
    public function setPlayersName($player, $naam) {
        
    }
    public function getPlayersName($player) {
        
    }
    /**
     * ask the database if the user is present 
     * @TODO where to obtain this information
     * @param {1,2} $nr
     * @return boolean
     */
    public function isPlayerKnown($nr) {
        return true;
    }
    /**
     * register the player in the databse
     * @param {1,2} $nr
     */
    public function setPlayer($nr) {
        
    }
    /**
     * check if the game has been started. 
     * @return boolean
     */
    public function isStarted() {
        return true;
    }
    /**
     * check if in the database (samen gameID?) both and different users are present
     * @return boolean
     */
    public function playersRegistered() {
        return true;
    }
    /**
     * for this game, set the 'beurt' to the nr (player)
     * @param {1,2} $nr
     */
    public function setBeurt($nr) {
    }
    /**
     * ask the database who's turn it is.
     */
    public function getBeurt() {
    }
    /**
     * set the score of the player
     */
    public function setScore($nr, $score)
    {
    }
    /**
     * set the scores to 0 (both players)
     */
    public function initScores() {
        
    }
    /**
     * register in database that game has been started
     */
    public function setStarted() {
        
    }
}

