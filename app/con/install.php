<?php

namespace App\Con;

Class Install
{
  //TODO probably should be a singleton
  // for the moment lets just get something working
  function __construct()
  {
  }
  function go()
  {

    //$this->download_wp();
    //$this->check_archive();
    $this->check_for_update();
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
    $conf=\App\Lib\Config::get();
    $req=new \App\Lib\Request($conf->get_wp_url());
    $req->get_head();
    print_r($req);
  }
}
