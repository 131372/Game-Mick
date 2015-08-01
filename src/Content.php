<?php


class Content { // should be interace, implementing either FILE or DATABASE

    const DELAYTIME = 30; // seconds

    public $players = Array();
    public $db;

    public function __construct($keepDatabase = true) {
        $this->keepDatabase = $keepDatabase;    
    }
    public function setDbInterface(dbInterface $dbI) {
        $this->db = $dbI;
    }
    public function init() {        
        $this->read();
        $this->sanitize();
    }

    public function __destruct() {
        
    }

    /**
     * get the other player's id, based on shared gameId (with me)
     * @param type $me  // id of person executing from client
     * @param type $gameId
     * @return type
     */
    public function getIdFromGameId($me, $gameId) {
        foreach ($this->players as $p) {
            if ($p->id != $me && $p->gameId == $gameId)
                return $p->id;
        }
        throw new Exception("could not locate ID of other player");
    }

    /**
     * is the ID a player (i.e. in players list)
     * @param type $id
     * @return boolean
     */
    public function isPlayer($id) {
        foreach ($this->players as $nr => $v) {
            if ($v->id == $id) {
                return true;
            }
        }
        return false;
    }

    /**
     * return players who are not participating in a game, order them on the registration number
     * @return array : player objects
     */
    public function sortedPlayers() {
        $arr = array();
        foreach ($this->players as $i => $p) {
            if ($p->gameId != '') {
                continue;    // player joined game
            }
            $arr[$p->nr] = $p;
        }
        ksort($arr);
        return $arr;
    }

    /**
     * add a player
     * @param Player $p     
     */
    public function add(Player $p) {        
        $this->truncate();
        foreach ($this->players as $id => $v) {
            if ($v->name === $p->name) {  // already in; replace
                $this->players[$id] = $p;
                $this->schrijf();
                return;
            }
        }
        // new player
        $cnt = count($this->players);
        $p->setNr($cnt);
        $this->players[] = $p; // should be check if p already in        
        $this->schrijf();        
    }

    /**
     * given a SHA, find the id of that person/player
     * @param type $sha
     * @return type
     * @throws Exception
     */
    function getIdFromSha($sha) {
        foreach ($this->players as $i => $v) {
            if ($v->sha === $sha) {
                return $v->id;
            }
        }
        throw new Exception("could not find player with a sha '$sha'");
    }

    /**
     * get the player of the given ID
     * @param type $id
     * @return type
     */
    public function get($id) {
        return $this->players[$this->findId($id)];
    }

    private function removePlayer($id) {
        unset($this->players[$this->findId($id)]);
    }

    /*
      public function hidePlayer() {

      }
     */

    public function getPlayer($id) {
        return $this->get($id);
    }

    /*
      public function merge() {
      $c = leesFile();
      if (!is_object($c)) {
      $this->schrijf();
      return;
      }
      foreach (array("players") as $k) {
      if (is_array($c->$k)) {
      $this->$k = array_merge($this->$k, $c->$k);
      }
      }
      $this->schrijf();
      }
     */

    public function dump() {
        print "<pre>Dump\n";
        print_r($this);
        print "</pre>";
    }

    /**
     * remove players (from file) when they are long ago registered
     * keep the file clean and small
     */
    public function sanitize() {
        foreach ($this->players as $i => $p) {
            if ($p->joinTime + self::DELAYTIME < time()) {
                $this->removePlayer($p->id);    //Verwijder hem
            }
        }
        // now remove duplicates (only when gameId is not set)
        foreach ($this->players as $i => $p) {
            if ($p->gameId != '') {
                $name = $p->name;
                foreach ($this->players as $j => $rp) {
                    if ($rp->gameId == $p->gameId)
                        continue;
                    if ($rp->name == $name) {
                        $this->removePlayer($rp->id);    //Verwijder hem
                    }
                }
            }
        }
    }
/**
 * attach unique generated id (as gameId) to the current player (id)
 * @param type $id  Id of the player initating (joining) the game. Other player will follow
 */
    public function joinGamer($id) {
        $gameId = uniqid("gameId"); // unique Id
        $this->players[$this->findId($id)]->gameId = $gameId; // and attach to this player

        $this->schrijf(); // overwrite (new information; new contents); hup in de database
    }
    
    /**
     * here is some speed to gain (but not needed right now).
     * find the array index for the player with the given id
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function findId($id) {
        foreach ($this->players as $i => $p) {
            if ($p->id == $id)
                return $i;
        }
        throw new Exception("Could not locate user with id '$id'");
    }

    public function getGameId($id) {
        return $this->players[$this->findId($id)]->gameId; // contains the GameId			
    }

    public function setGameId($id, $gameId) {
        $this->players[$this->findId($id)]->gameId = $gameId;
        $this->schrijf();
    }

    public function getName($id) {
        return $this->players[$this->findId($id)]->name;
    }

    public function read() {
        $c = $this->db->read();
        $this->players = array();
        foreach ($c as $po) {
            $p = new Player();
            $p->set($po);       // via magic calls __set(...)
            $this->players[] = $p;
        }
    }

    public function schrijf() {
        $this->db->write($this);
    }

    public function truncate() {        
        $this->db->truncate();
    }

}
