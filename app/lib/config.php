<?php
namespace App\Lib;
Class Config Extends Registry
{
  public $cfg;
  protected function __construct()
  {
    $file = 'app/cfg/config.yml';
    if(file_exists($file))
      $this->cfg = \sfYaml::load($file);
    else
      die("no config\n");
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
          if(isset($this->cfg[$args[0]][$this->cfg['env']]))
            return $this->cfg[$args[0]][$this->cfg['env']];
          elseif(isset($this->cfg[$args[0]]))
            return $this->cfg[$args[0]];
        break;
      }
		}
  }
}
