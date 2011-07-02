<?php

Class Dev
{
	static private $instance;

  private
	$group_reg='/
			(^init)|
			(^news)|
			(^routing)|
			(IMPORTANT)|
			.*
			/x';

  private function __construct(){
			$this->time=microtime_float();
			$this->lasttime=microtime_float();}

	private function get_instance(){
		if(self::$instance==null)
			self::$instance=New Dev();
		return self::$instance; }

  public static function log($group,$message){
    if(defined("DEV"))
			Dev::get_instance()->dev_log($group,$message);}

  private function dev_log($group,$message){
		if(preg_match($this->group_reg,$group)){
		  $firephp=FirePHP::getInstance(true)
		    ->log(sprintf("%.3f %.3f %'_30s - %s",
					(microtime_float()-$this->time),
					(microtime_float()-$this->lasttime),
					$group,
					$message));
			$this->lasttime=microtime_float();
			if(0){
			  $file_handle = fopen(DEVLOG, 'a');
			  fwrite($file_handle, 
		      sprintf("%.3f %.3f %'_30s - %s",
					  (microtime_float()-$this->time),
					  (microtime_float()-$this->lasttime),
					  $group,
					  $message."\n"));
			  fclose($file_handle);}}}

  public static function dump($group,$key,$message){
    if(defined("DEV"))
		  Dev::get_instance()->dev_dump($group,$message);}

  private function dev_dump($group,$message){
		if(preg_match($this->group_reg,$group))
		  $firephp=FirePHP::getInstance(true)
		    ->dump($key,$message);}
			
}
