<?php

namespace App\Con;

use App\Lib\Config as Config;
use App\Lib\Request as Request;
use App\Lib\Registry as Registry;

Class Install Extends Registry 
{
  public function go()
  {
    $conf=Config::get_instance();
    $url=$conf->get_wp_url();
    $latest=$this->get_latest_version_name($url);
    $latest='wordpress-3.2.tar.gz';
    $tmp_file='app/up/'.$latest;
    $this->download_wp($tmp_file,$url);
    $tmp_output='app/up/';
    if($this->unpack_archive($tmp_file,$tmp_output))
      echo "\nextracted to $tmp_output\n";
  }
  private function download_wp($file_name,$url)
  {
    $req=new Request();
    $req->download($file_name,$url,true);
  }
  private function unpack_archive($file,$tmp_output)
  {
    include('Archive/Tar.php');
    $tar=new \Archive_Tar($file,'gz');
    $list=$tar->listContent();
    if($list)
    {
      $tar->extract($tmp_output);
      return true;
    }
  }
  private function check_for_update()
  {
    $wp_version=3.1;
    $php_version=phpversion();
    $url = "http://api.wordpress.org/core/version-check/1.5/"
      ."?version=$wp_version&php=$php_version";
      /*
      ."&locale=$locale"
      ."&mysql=$mysql_version&local_package=$local_package"
      ."&blogs=$num_blogs&users={$user_count['total_users']}"
      ."&multisite_enabled=$multisite_enabled";
      */
    $conf=Config::get_instance();
    $req=new Request($url);
    echo $req->get_body();
  }
  private function get_latest_version_name($url)
  {
    $req=new Request($url);
    $req->get_head();
    $name=$req->get_filename();
    if($name)
      return $name;
  }
}

