<?php
namespace App\Lib;
Abstract class Registry
{
  private static $instances;

  protected function __construct() 
  {
  }

  public static function get_instance() 
  {
    $c = get_called_class();
    if (!isset(self::$instances[$c])) {
      self::$instances[$c] = new $c;
    }

    return self::$instances[$c];
  }

  public function __clone()
  {
    trigger_error('Clone is not allowed.', E_USER_ERROR);
  }

}
