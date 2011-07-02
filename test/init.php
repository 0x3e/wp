<?php 
Init::go();
Class Init
{
  public static function go()
  {
    chdir(dirname(__File__)."/..");
    include("SymfonyComponents/YAML/sfYaml.php");
    include("app/lib/autoloader.php");
    $paths_file = 'test/cfg/paths.yml';
    $paths_cfg = sfYaml::load($paths_file);
    if(!is_array($paths_cfg))
      exit($paths_file);
    $paths=implode(PATH_SEPARATOR,$paths_cfg);
    set_include_path(get_include_path() . PATH_SEPARATOR . $paths);
    spl_autoload_register("Autoloader::load");
  }
}
