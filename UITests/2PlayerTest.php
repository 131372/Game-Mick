<?php

include_once 'bootstrap.php';

include_once 'dbInterface.php';
include_once 'dbSetup.php';
include_once 'Player.php';
include_once 'Content.php';
 
// from https://raw.githubusercontent.com/giorgiosironi/phpunit-selenium/master/Tests/Selenium2TestCaseTest.php
//use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
include_once __DIR__ . '/seleniumParts/loader.php';


class TwoPlayerTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        //global $dbh;
        //$dbh = new Connection($dbName, $dbUser, $dbWachtwoord);
        
        $c = new Content(); 
        $c->setDbInterface(new dbInterface());
        $c->init();
        $c->truncate();
    }
    use WebDriverAssertions,
        WebDriverDevelop,
        WebDriverIF,
        WebDriverMethodsIF;

    private $verbose = false; // true: shows which trait
    protected $urlBase = "http://localhost/2Players/UItests/";

    use Develop;

} class Y {

    use Basic;

use Click;

use Select;

use Form;

use InputTyping;

use JavaScript;

use jQuery;

use Links;

use Events;

use CSS;

use Frames;

use Windows;

use Mouse;

use Session;

use Cookies;

use Navigate;
}
