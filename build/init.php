<?php
namespace x3e\Build;
Init::go();
Class Init 
{
  //TODO probably should be a singleton
  // for the moment lets just get something working
  function __construct()
  {
  }
  public static function go()
  {
    chdir(dirname(__File__)."/..");
    include("SymfonyComponents/YAML/sfYaml.php");
    include("app/lib/autoloader.php");
    $paths_file = 'build/cfg/paths.yml';
    $paths_cfg = \sfYaml::load($paths_file);
    //TODO raise exception
    if(!is_array($paths_cfg))
      exit("not found ".$paths_file);
    $paths=implode(PATH_SEPARATOR,$paths_cfg);
    set_include_path(get_include_path() . PATH_SEPARATOR . $paths);
    spl_autoload_register("Autoloader::load");
    $manifest = \sfYaml::load('manifest.yml');
  }
}

