<?php

namespace App\Con;

use App\Lib\Config as Config;
use App\Lib\Request as Request;
use App\Lib\Registry as Registry;

Class Install Extends Registry 
{
  function go()
  {
    $this->check_for_update();
    //$this->download_wp();
    //$this->check_archive();
  }
  private function download_wp()
  {
    $fr = fopen("http://wordpress.org/latest.tar.gz", "rb");
    $fw = fopen("app/up/latest.tar.gz", "wb");
    while(!feof($fr)) 
    {
      fwrite($fw,fread($fr,8192),8192);
    }
  }

  private function check_archive()
  {
    include('Archive/Tar.php');
    $file=new \Archive_Tar('app/up/latest.tar.gz','gz');
    //print_r($file->listContent());
  }
  private function check_for_update()
  {
    $conf=Config::get_instance();
    $req=new Request($conf->get_wp_url());
    $req->get_head();
    $name=$req->get_filename();
    if($name)
      var_dump($name);
  }
}
