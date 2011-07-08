<?php 
namespace App\Lib;
Class Kv
{
  public function __construct($name==null,$connection=null)
  {
    $conf=Config::get_instance();
    if(!$connection)
      $connection=new PDO();
  }
}
