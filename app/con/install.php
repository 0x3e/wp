<?php

namespace App\Con;

use App\Lib\Config as Config;
use App\Lib\Request as Request;
use App\Lib\Registry as Registry;
use App\Lib\Kv as Kv;

Class Install Extends Registry 
{
  public function go()
  {
    $conf=Config::get_instance();
    $wp_conf=$conf->get_wp();
    $wp_url=$wp_conf['url'];
    $wp_dev_conf=$wp_conf['dev'];

    $latest=$this->get_latest_version_name($wp_url);
    if(!$latest)
      return;
    $current=new Kv('current_version');
    $tmp_file='app/up/'.$latest;
    $this->download_wp($tmp_file,$wp_url);
    $tmp_output='app/up/';
    if($this->unpack_archive($tmp_file,$tmp_output))
    {
      $name=substr($latest,0,-7);
      echo "\nextracted to $tmp_output\n";
      rename($tmp_output."wordpress","app/vnd/wp/".$name);
      echo "\nrenamed ".$tmp_output."wordpress"." app/vnd/wp/".$name."\n";
      symlink("app/vnd/wp/".$name,"tmp_wp_link");
      rename("tmp_wp_link","wp");
      echo "\nsymlinked app/vnd/wp".$name." wp\n";
    }
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

