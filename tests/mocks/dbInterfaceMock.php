<?php

use \Mockery as m;

trait dbInterfaceMock {

    public $mockData;

    private function mockDbI() {
        $dbI = m::mock('dbInterface');

        $dbI->shouldReceive('name')->andReturn(__TRAIT__);
        $dbI->shouldReceive('read')->andReturn($this->mockData);
        /*
          $dbI->shouldReceive('read')->andReturnUsing(function() {
          return $this->mockData;
          });
          /*
          $dbI->shouldReceive('truncate')->withNoArgs(m::on(function() {
          $this->mockData = array();
          return true;
          }));
         * 
         */
        $dbI->shouldReceive('truncate')->andReturnUsing(function() {
            $this->mockData = array();
            return null;
        });

        $dbI->shouldReceive('write')->with(m::on(function(Content $obj) {
                    $this->mockData = $obj->players;
                    return true;
                }));

        return $dbI;
    }

}
