<?php
namespace App\Lib;
Class Request 
{
  private $info;
  private $header;
  private $url;

  public function __construct($url=null)
  {
    $this->url = $url;
  }

  public function get_head() 
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->url);
    curl_setopt($curl, CURLOPT_FILETIME, true);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $raw_header = curl_exec($curl);
    $this->info = curl_getinfo($curl);
    curl_close($curl);
    $this->set_header($raw_header);
  }

  public function set_url($url)
  {
    $this->url=$url;
  }

  public function get_filetime() 
  {
    return $this->info['filetime'];
  }

  public function get_filename() 
  {
    if(isset($this->header['Content-Disposition']['filename']))
      return $this->header['Content-Disposition']['filename'];
  }

  private function set_header($header)
  {
    $header = explode("\n",$header);
    $i=0;
    foreach($header as $line)
    {
      $i++;
      if($i==1)
      {
        $this->header['Response']=$line;
      }
      else
      {
        $tmp=explode(":",$line);
        if(isset($tmp[1]))
        {
          $this->header[$tmp[0]]=$tmp[1];
        }
        if($tmp[0]=='Content-Disposition')
        {
          $cd=array();
          $con_disp=explode(';',$tmp[1]);
          foreach($con_disp as $c)
          {
            $c=trim($c);
            if($c=='attachment')
            {
              $cd['attachment']='attachment';
            }
            else
            {
              $x=explode('=',$c);
              $cd[$x[0]]=$x[1];
            }
          }
          $this->header[$tmp[0]]=$cd;
        }
      }
    }
  }
}
