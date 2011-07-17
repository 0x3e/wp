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
    $tmp_output='app/up/';
    if(!is_writable($tmp_output))
    {
      if(!file_exists("$tmp_output"))
        mkdir($tmp_output);
      chmod($tmp_output, 0777);
    }
    $conf=Config::get_instance();
    $wp=$conf->get_wp();

    $latest=$this->get_latest_version_name($wp['url']);
    $tmp_file=$tmp_output.$latest;
    if(!$latest)
      return;
    $c=new Kv('current_version');
    $current=$c->get_v();
    if($current==$latest)
    {
      echo "up to date\n";
      return;
    }
    elseif(!$current) 
    {
      echo "all new\n";
    }
    echo "downloading update\n";
    $this->download_wp($tmp_file,$wp['url']);
    if($this->unpack_archive($tmp_file,$tmp_output))
    {
      $name=substr($latest,0,-7);
      echo "\nextracted to $tmp_output\n";
      if(!file_exists("app/vnd/wp/"))
        mkdir("app/vnd/wp/");
      rename($tmp_output."wordpress","app/vnd/wp/".$name);
      echo "\nrenamed ".$tmp_output."wordpress"." app/vnd/wp/".$name."\n";
      symlink("app/vnd/wp/".$name,"tmp_wp_link");
      copy('app/cfg/wp-config.php','tmp_wp_link/wp-config.php');
      $cwd=getcwd();
      chdir('app/vnd/wp/'.$name.'/wp-content/themes/');
      symlink('../../../../../thm/wp/0x3e','0x3e');
      chdir($cwd);
      rename("tmp_wp_link","wp");
      echo "\nsymlinked app/vnd/wp".$name." wp\n";
      $c->set_v($latest);
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

