<?php
namespace App\Lib;
Abstract class Singleton
{
  private static $instance;

  protected function __construct() 
  {
  }

  public static function get() 
  {
    if (!isset(self::$instance)) {
      $c = get_called_class();
      self::$instance = new $c;
    }

    return self::$instance;
  }

  public function __clone()
  {
    trigger_error('Clone is not allowed.', E_USER_ERROR);
  }

}
