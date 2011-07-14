<?php
namespace App\Lib;
use PDO as PDO;

Class Database
{
  public static function get_handle($conf=null)
  {
    if(!$conf)
      $conf=Config::get_instance()->get_db();
    $handle=new PDO('mysql:'
      .'dbname='.$conf['db'].';'
      .'host='.$conf['host']
      , $conf['user'], $conf['password']
    );
    return $handle;
  }
}
