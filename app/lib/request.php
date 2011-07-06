<?php
namespace App\Lib;
Class Request 
{
  private $info;
  private $header;
  private $url;
  private $body;

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
    $this->set_downloaded_head($raw_header);
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

  private function set_downloaded_head($header)
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
  public function download($download_path=null,$url=null)
  {
    if(!$download_path)
      $download_path=tmpfile();

    $fw = fopen($download_path, "wb");

    if(!$url)
      $url=$this->url;

    if(!$url||!$download_path)
      return false;

    $fr = fopen($url, "rb");
    while(!feof($fr)) 
    {
      fwrite($fw,fread($fr,8192),8192);
    }
    return $download_path;
  }
  public function get_body($url=null)
  {
    if(!$url)
      $url=$this->url;

    if(!$this->body) 
      $this->body=file_get_contents($url);

    return $this->body;
  }
}
