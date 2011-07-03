<?php 
namespace App;
Init::go();
Class Init
{
  public static function go()
  {
    chdir(dirname(__File__)."/..");
    include("SymfonyComponents/YAML/sfYaml.php");
    include("app/lib/autoloader.php");
    spl_autoload_extensions(".php");
    set_include_path(get_include_path() . './');
    spl_autoload_register('App\Lib\Autoloader::load');
    $install=new Con\Install();
    $install->go();
  }
}
