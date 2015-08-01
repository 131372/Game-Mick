<?php
set_include_path(get_include_path() . PATH_SEPARATOR . "./");

include_once 'dbInit.php';
include_once '../dbConnection.php';
// we kunnen nu met een database praten


class Server {
    private $data = array();  
    public function __construct() {
        
    }
    private function getMethod($id) {
        return $_GET[$id];  // must be checked and protected
    }
    private function postMethod($id) {
        return $_POST[$id];  // must be checked and protected
    }
    private  function parseCmd() {
        $this->data['cmd'] = $this->getMethod('action');
    }
    private function setInfo($data) {
        $this->data['info'] = $data;
    }
    private function setStatus($status) {
        $this->data['status'] = $status;
    }
    public function run() {
        $this->parseCmd();
        switch($this->cmd) {
            case 'initialize':
                $p1 = $this->getPlayerFromDatabase(1);    // name of first player
                $p2 = $this->getPlayerFromDatabase(2);
                $this->setInfo($p1);
                $this->setInfo($p2);
                $this->setInfo(array('namenVanSpelers' => array($p1, $p2)));    // zo kan het ook
                $this->setStatus('wachtenOpInput');
                break;
            default:
                $this->setInfo("Command niet geldig");
                $this->setStatus('serverFout');
                break;
        }
        $this->respond();
    }
    
    /**
     * send information back to server
     */
    private function respond() {
        $data = json_encode($this->data);
        die($data); // echo and quit
    }
}
