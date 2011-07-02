<?php
Init::go();
Class Init
{
  //TODO probably should be a singleton
  // for the moment lets just get something working
  function __construct()
  {
  }
  function go()
  {
    $manifest = sfYaml::load('manifest.yml');
    print_r(get_include_path());
    //print_r(
  }
}
