<?php
class Test_dependencies extends PHPUnit_Framework_TestCase
{
  public function test_deps()
  {
    $dep=new Dependencies();
    $this->assertTrue($dep->ok());
  }
}

