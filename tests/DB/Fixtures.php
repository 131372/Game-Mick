<?php

/**
 * Description of Fixtures
 *
 * @author jacob
 */
trait Fixtures {

    use createTables;

    private $pdo;

    private function getPDO() {
        $this->pdo = $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set Errorhandling to Exception
    }

    private function dropPDO() {
        $this->pdo = null;
    }

    public function getConnection() {
        return $this->createDefaultDBConnection($this->pdo, ':memory:');
    }

    protected function setUp() {
        //$this->getPDO();
        $this->setDBTables();
        parent::setUp();
    }

    protected function tearDown() {
        $this->dropTables();    // drop tables
        //$this->dropPDO();       // then drop pdo connection

        parent::tearDown();
    }

    private $fixtures = array(
        array("players", "xml", ""),        
    );

    private function getFixturePath() {
        $d = DIRECTORY_SEPARATOR;
        return dirname(__FILE__) . "${d}..${d}data${d}";
    }

    public function getDataSet() {
        $compositeDs = new
                PHPUnit_Extensions_Database_DataSet_CompositeDataSet(array());

        foreach ($this->fixtures as $fixture) {
            $fName = $fixture[0];
            $fExt = $fixture[1];

            $path = $this->getFixturePath() . "$fName.$fExt";
            if ($fExt == "xml") {
                $ds = $this->createXMLDataSet($path);
            } else {    // only csv allowed
                $ds = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
                $ds->addTable($fixture[2], $path);
            }
            $compositeDs->addDataSet($ds);
        }
        return $compositeDs;
    }

    private function getXmlFile($fName) {
        return $this->getFixturePath() . "$fName.xml";
    }

    private function getXmlContents($name) {
        return simplexml_load_file($this->getXmlFile($name));
    }

}
