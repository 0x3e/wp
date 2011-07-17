<?php
include('app/vnd/FirePHP.php');
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
			$this->time=self::microtime_float();
			$this->lasttime=self::microtime_float();}

	private static function get_instance(){
		if(self::$instance==null)
			self::$instance=New Dev();
		return self::$instance; }

  public static function log($group,$message){
    if(defined("DEV"))
			Dev::get_instance()->dev_log($group,$message);}

  public static function log_print($group,$ob){
    if(defined("DEV"))
			Dev::get_instance()->dev_log($group,print_r($ob,true));}

  private function dev_log($group,$message){
		if(preg_match($this->group_reg,$group)){
		  if(0){$firephp=FirePHP::getInstance(true)
		    ->log(sprintf("%.3f %.3f %'_30s - %s",
					(self::microtime_float()-$this->time),
					(self::microtime_float()-$this->lasttime),
					$group,
					$message));}
			$this->lasttime=self::microtime_float();
			if(1){
			  $file_handle = fopen(DEV_LOG, 'a');
			  fwrite($file_handle, 
		      sprintf("%.3f %.3f %'_30s - %s",
					  (self::microtime_float()-$this->time),
					  (self::microtime_float()-$this->lasttime),
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

  public static function microtime_float()
  {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }
			
}
