<?php 
namespace App\Lib;
use App\Lib\Database as Database;
use PDO as PDO;

Class Kv
{
  public function __construct($k=null)
  {
    $this->k=$k;
    $dh=$this->get_dh();
    $prefix=$this->get_prefix();
    $st=$dh->prepare('select k, v from '.$prefix."kv where k = :k");
    $st->bindParam(':k',$k, PDO::PARAM_STR, 256);
    $st->execute();
    if($st->errorCode()=='42S02')
    {
      $st=null;
      $this->create_table($dh);
    }
    else
    {
      $this->v=$st->fetch();
      $st=null;
    }
    $dh=null;
  }
  public function __toString()
  {
    return $this->v;
  }
  public function get_v()
  {
    if(isset($this->v))
      return $this->v;
  }
  public function set_v($v)
  {
    $k=$this->k;
    $dh=$this->get_dh();
    $prefix=$this->get_prefix();
    $st=$dh->prepare('select k, v from '.$prefix."kv where k = :k");
    $st->bindParam(':k',$k, PDO::PARAM_STR, 256);
    $st->execute();
    $f=$st->fetch();
    $st=null;
    if($f)
    {
      $st=$dh->prepare('update '.$prefix."kv set v=:v where k = :k");
      $st->bindParam(':k',$k, PDO::PARAM_STR, 256);
      $st->bindParam(':v',$v, PDO::PARAM_STR, 1024);
      if($st->execute())
      {
        $st=null;
        $this->v=$v;
        return true;
      }
    }
    else
    {
      $st=$dh->prepare('insert into '.$prefix."kv (k,v) values (:k,:v)");
      $st->bindParam(':k',$k, PDO::PARAM_STR, 256);
      $st->bindParam(':v',$v, PDO::PARAM_STR, 1024);
      if($st->execute())
      {
        $st=null;
        $this->v=$v;
        return true;
      }
    }
        
    print_r($st->errorInfo());
    $st=null;
  }
  private function create_table($dh)
  {
    $prefix=$this->get_prefix();
    $st=$dh->prepare('
    CREATE TABLE IF NOT EXISTS '.$prefix.'kv (
    `k` VARCHAR(256) NOT NULL ,
    `v` VARCHAR(1024) NULL ,
    PRIMARY KEY (`k`)
    )
    DEFAULT CHARACTER SET = utf8
    COLLATE = utf8_bin');
    $st->execute();
    $st=null;
  }
  private function get_dh()
  {
    $conf=Config::get_instance()->get_db();
    $prefix=$conf['prefix'];
    return Database::get_handle($conf);
  }
  private function get_prefix()
  {
    $conf=Config::get_instance()->get_db();
    return $conf['prefix'];
  }
}
