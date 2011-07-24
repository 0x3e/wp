<?php 
namespace App;
use \App\Con\Install as Install;
use \Dev as Dev;
Class Init
{
  public static function install()
  {
    self::base();
    $install=Install::get_instance();
    $install->go();
  }
  public static function patch()
  {
    self::base();
    $install=Install::get_instance();
    $install->move_wp_items_into_place();
  }
  public static function wp()
  {
    date_default_timezone_set('Australia/Sydney');
    $cwd=getcwd();
    chdir(dirname(__File__)."/..");
    include("SymfonyComponents/YAML/sfYaml.php");
    include("app/lib/autoloader.php");
    spl_autoload_extensions(".php");
    set_include_path(get_include_path() . ':../../../../:./');
    spl_autoload_register('App\Lib\Autoloader::load');
    error_reporting(-1);
    ini_set('error_log',dirname(__File__).'/log/error.log');
    define('DEV', 'true');
    define('DEV_LOG', dirname(__File__).'/log/dev.log');
    ini_set('display_errors',0);
    chdir($cwd);
    Dev::log("info","\n\n\n".'\App\Init::wp complete');
  }
  private static function base()
  {
    date_default_timezone_set('Australia/Sydney');
    chdir(dirname(__File__)."/..");
    include("SymfonyComponents/YAML/sfYaml.php");
    include("app/lib/autoloader.php");
    spl_autoload_extensions(".php");
    set_include_path(get_include_path() . ':./');
    spl_autoload_register('App\Lib\Autoloader::load');
  }
}

