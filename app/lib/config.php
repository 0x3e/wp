<?php
namespace App\Lib;
Class Config Extends Singleton 
{
  public $cfg;
  protected function __construct()
  {
    $file = 'app/cfg/config.yml';
    $this->cfg = \sfYaml::load($file);
  }
  public function __call($function, $args)
  {
    $pos=strpos($function, "_");
    $prefix=substr($function,0,$pos+1);
		if($prefix=='get_'||$prefix=='set_')
		{
      array_unshift($args,substr($function,$pos+1));
      switch($prefix)
      {
        case "get_":
          return $this->cfg[$args[0]];
        break;
        case "set_":
          return $this->cfg[$args[0]] = $args[1];
        break;
      }
		}
  }
}
