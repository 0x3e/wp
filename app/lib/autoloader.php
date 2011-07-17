<?php 
namespace App\Lib;
Class Autoloader
{
  public static function load($name)
  {
    $n=strtolower(str_replace('\\','/',$name));
    @include($n.'.php');
  }
}
