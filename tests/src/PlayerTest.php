<?php

/**
 */
class PlayerTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Player
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Player::__construct
     */
    public function testConstructor() {
        $o = new Player();
        $o->init("abc");
        $this->assertEquals("abc", $o->name);
        $this->assertContains("id", $o->id);
        $this->assertEquals(15, strlen($o->id));
    }

    /**
     * @covers Player::getGameId         
     */
    public function testGetGameId() {
        $o = new Player();
        $o->init('aPlayer');
        $this->assertEquals('', $o->getGameId(), 'should be empty');
        $o->setGameId('id12345');
        $this->assertEquals('id12345', $o->getGameId(), 'should NOT be empty');
    }

    /**
     * @covers Player::setGameId
     */
    public function testSetGameId() {
        $o = new Player();
        $o->init('aPlayer');
        $o->setGameId('id12345');
        $this->assertEquals('id12345', $o->getGameId(), 'should NOT be empty');
    }

    /**
     * @covers Player::toPrint
     * @todo   Implement testToPrint().
     */
    /*
    public function testToPrint() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
     *
     */

    /**
     * @covers Player::toString
     * @todo   Implement testToString().
     */
    public function testToString() {
        $o = new Player();
        $o->init('aPlayer');
        $this->assertEquals('Player aPlayer', $o->toString());
    }

}
