<?php Class Autoloader
{
  public static function load($name)
  {
    if(include(strtolower($name.'.php')));
  }
}
