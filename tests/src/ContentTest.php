<?php

include_once 'dbInterfaceMock.php';

use \Mockery as m;

class ContentTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Content
     */
    protected $object;

    use dbInterfaceMock;

    private function newContent() {
        $object = new Content();
        $dbI = $this->mockDbI();
        $object->setDbInterface($dbI);
        $object->init();
        return $object;
    }
    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->mockData = Array();       // must be before newContent
        $this->object = $this->newContent();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
//        $this->object->truncate();
        $this->mockData = array();
        m::close();
    }

    public function testConstructor() {
        
    }
//} class Y {

    /**
     * @covers Content::getIdFromGameId
     */
    public function testGetIdFromGameId() {
        $o = $this->object;
        $gameId = 'g123';
        $p1 = new Player();
        $p1->init('mememe');
        $p1->setGameId($gameId);   // should be mocked
        $p2 = new Player();
        $p2->init('other');
        $p2->setGameId($gameId);   // should be mocked
        $o->add($p1);
        $o->add($p2);
        $me = $p1->id;
        $other = $p2->id;
        $this->assertEquals($other, $o->getIdFromGameId($me, $gameId));
    }

    /**
     * @covers Content::isPlayer
     * @todo   Implement testIsPlayer().
     */
    public function testIsPlayer() {
        $o = $this->object;
        $p1 = new Player();
        $p1->init('mememe');
        $o->add($p1);
        $this->assertTrue($o->isPlayer($p1->id));
    }

    /**
     * @covers Content::add    
     */
    public function testAdd() {
        $o = $this->object;
        $this->assertEquals(0, count($o->players));
        $gameId = 'g123';
        $p1 = new Player();
        $p1->init('mememe');
        $p2 = new Player();
        $p2->init('other');
        $o->add($p1);
        $this->assertEquals(1, count($o->players));
        // test on the right one?

        $o->add($p2);
        $this->assertEquals(2, count($o->players));
    }

    /**
     * @covers Content::getIdFromSha     
     */
    public function testGetIdFromSha() {
        $o = $this->object;
        $this->assertEquals(0, count($o->players));
        $gameId = 'g123';
        $p1 = new Player();
        $p1->init('mememe');
        $sha = $p1->sha;

        $o->add($p1);
        $this->assertEquals($p1->id, $o->getIdFromSha($sha));
    }

    /**
     * @covers Content::getIdFromSha     
     */
    public function testGetIdFromShaWithException() {
        $o = $this->object;
        $this->assertEquals(0, count($o->players));
        $gameId = 'g123';
        $p1 = new Player();
        $p1->init('mememe');
        $sha = $p1->sha;
        $this->setExpectedException(
                'Exception', "could not find player with a sha '$sha'"
        );
        $this->assertNotEquals($p1->id, $o->getIdFromSha($sha));    // not in DB
    }

    /**
     * @covers Content::get
     */
    public function testGet() {
        $o = $this->object;
        $p1 = new Player();
        $p1->init('mememe');
        $o->add($p1);
        $this->assertEquals($p1, $o->get($p1->id));
    }

    /**
     * @covers Content::hidePlayer
     * @todo   Implement testHidePlayer().
     */
    /*
      public function testHidePlayer() {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete(
      'This test has not been implemented yet.'
      );
      }
     */

    /**
     * @covers Content::getPlayer
     */
    public function testGetPlayer() {
        $o = $this->object;
        $p1 = new Player();
        $p1->init('mememe');
        $o->add($p1);
        $this->assertEquals($p1, $o->get($p1->id));
    }

    public function testTruncate() {
        $o = $this->object;
        $this->assertEquals(0, count($this->mockData));
        $this->assertEquals(0, count($o->players));
        foreach (array("abc", "def", "ghi") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);        // uses truncate inside!!
        }
        $o->truncate();

        $this->assertEquals(3, count($o->players));
        $this->assertEquals(0, count($this->mockData));
        foreach (array("bli", "bla", "blo") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);        // uses truncate inside!!
        }
        $this->assertEquals(6, count($o->players));
        $this->assertEquals(6, count($this->mockData)); // previous three and new ones
        $o->truncate();

        $this->assertEquals(6, count($o->players));
        $this->assertEquals(0, count($this->mockData));
    }

    /**
     * @covers Content::read
     */
    public function testRead() {
// read from database, must ensure players[] is filled properly
        $o = $this->object;
        $this->assertEquals(0, count($this->mockData));
        $this->assertEquals(0, count($o->players));
        foreach (array("abc", "def", "ghi") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
        }
        $o->db->read();
        $this->assertEquals(3, count($o->players));

        // now assume second person get the same Content (interface).
        $this->assertEquals(3, count($this->mockData));
        
        $oo = $this->newContent();
        $this->assertEquals(3, count($this->mockData), "attached MOCK");
        $this->assertEquals(3, count($oo->players), "implicit read (via init())");

        $oo->read();    // data should have been shared.        
        
        $this->assertEquals(3, count($oo->players));        
        $this->assertEquals(3, count($o->players));        
        $this->assertEquals($o->players, $oo->players);
    }

    /**
     * @covers Content::merge
     * @todo   Implement testMerge().
     */
    /*
      public function testMerge() {
      // Remove the following lines when you implement this test.
      $this->markTestIncomplete(
      'This test has not been implemented yet.'
      );
      }
     */

    /**
     * @covers Content::schrijf
     */
    public function testSchrijf() {
        $o = $this->object;
        $this->assertEquals(0, count($o->players));
        foreach (array("abc", "def", "ghi") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
        }
        $this->assertEquals(3, count($o->players));
        foreach (array("abc", "def", "ghi", "jkl") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
        }
        $this->assertEquals(4, count($o->players));
    }

    /**
     * @covers Content::sanitize
     */
    public function testSanitize() {
        $o = $this->object;
        $this->assertEquals(0, count($o->players));
        foreach (array("abc", "def", "ghi") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
        }
        $this->assertEquals(3, count($o->players));
        $OldName = $o->players[2]->name;
        $o->players[2]->joinTime -= 100;
        $o->sanitize();
        $this->assertEquals(2, count($o->players));
        foreach ($o->players as $p) {
// correct one from database?
            $name = $p->name;
            $this->assertNotEquals($OldName, $name);
        }
    }

    /**
     * @covers Content::joinGamer     
     */
    public function testJoinGamer() {
        $o = $this->object;
        $this->assertEquals(0, count($o->players));
        foreach (array("abc", "def", "ghi") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
        }
        $id = $o->players[2]->id;
        $this->assertEquals('', $o->players[2]->gameId);
        $o->joinGamer($id);
        $this->assertNotEquals('', $o->players[2]->gameId);
    }

    /**
     * @covers Content::findId
     */
    public function testFindId() {
        $o = $this->object;
        $ids = array();
        $this->assertEquals(0, count($o->players));
        foreach (array("abc", "def", "ghi") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
            $ids[] = $p->id;
        }
        foreach ($ids as $i => $id) {
            $this->assertEquals($i, $o->findId($id));
        }
    }

    public function testFindIdException() {
        $o = $this->object;
        foreach (array("abc", "def", "ghi") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
        }
        $id = 'qwerty';
        $this->setExpectedException('Exception', "Could not locate user with id '$id'");
        $this->assertEquals(0, $o->findId($id));
    }

    /**
     * @covers Content::getGameId
     */
    public function testGetGameId() {
        $o = $this->object;
        $fakeId = "fakeGameId";
        foreach (array("abc", "def", "ghi") as $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
            $p->gameId = $fakeId;   // not needed to test from database
            $this->assertEquals($fakeId, $o->getGameId($p->id));
        }
    }

    /**
     * @covers Content::setGameId
     */
    public function testSetGameId() {
        $o = $this->object;
        $fakeId = "fakeGameId";
        foreach (array("abc", "def", "ghi") as $i => $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
            $o->setGameId($p->id, $fakeId);   // not needed to test from database
            $this->assertEquals($fakeId, $o->getGameId($p->id));
        }
    }

    /**
     * @covers Content::getName     
     */
    public function testGetName() {
        $o = $this->object;
        foreach (array("abc", "def", "ghi") as $i => $name) {
            $p = new Player();
            $p->init($name);
            $o->add($p);
            $this->assertEquals($name, $o->getName($p->id));
        }
    }

    public function testSortedPlayers() {
        $o = $this->object;
        $ids = array();
        for ($i = 0; $i <= 12; $i++) {
            $name = "abc_" . $i;
            $p = new Player();
            $p->init($name);
            $ids[] = $p->id;
            $o->add($p);
        }
        $o->joinGamer($ids[0]);
        $gameId = $o->getGameId($ids[0]);
        $o->setGameId($ids[1], $gameId);
        foreach ($o->sortedPlayers() as $i => $p) {
            $this->assertEquals('', $p->gameId);
            if (!isset($nr)) { // first entry
                $nr = $p->nr;
            } else {
                $this->assertGreaterThan($nr, $p->nr);  // actual must be greater!
                $nr = $p->nr;
            }
        }
// well now have 3 other players join 1 game
        $o->joinGamer($ids[5]);
        $gameId2 = $o->getGameId($ids[5]);
        $o->setGameId($ids[12], $gameId2);
        $o->setGameId($ids[11], $gameId2);
        unset($nr);
        foreach ($o->sortedPlayers() as $i => $p) {
            $this->assertEquals('', $p->gameId);
            if (!isset($nr)) { // first entry
                $nr = $p->nr;
            } else {
                $this->assertGreaterThan($nr, $p->nr);
                $nr = $p->nr;
            }
        }
    }

}
