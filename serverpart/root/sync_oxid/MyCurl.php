<?PHP
/************************************************

MyCRM OXID-to-Sugar Connector

The Program is provided AS IS, without warranty. You can redistribute it and/or modify it under the terms of the GNU Affero General Public License Version 3 as published by the Free Software Foundation.

For contact:
MyCRM GmbH
Hirschlandstrasse 150
73730 Esslingen
Germany

www.mycrm.de
info@mycrm.de

****************************************************/
class OxidMyCurl
{
  var $getHeaders = true;//headers will be added to output
  var $getContent = true; //contens will be added to output
  var $followRedirects = true; //should the class go to another URL, if the current is "HTTP/1.1 302 Moved Temporarily"
  
  var $fCookieFile;
  var $fSocket;
  
  function MyCurl()
  {
  global $path;
    $this->fCookieFile = tempnam($path, "g_");
  }

  function init()
  {
    return $this->fSocket = curl_init();
  }
  
  function setopt($opt, $value)
  {
    return curl_setopt($this->fSocket, $opt, $value);
  }
  
  function load_defaults()
  {
    $this->setopt(CURLOPT_RETURNTRANSFER, 1);
    #$this->setopt(CURLOPT_FOLLOWLOCATION, $this->followRedirects);
    #$this->setopt(CURLOPT_REFERER, "http://google.com");
    $this->setopt(CURLOPT_VERBOSE, false); 
    $this->setopt(CURLOPT_SSL_VERIFYPEER, false);
    $this->setopt(CURLOPT_SSL_VERIFYHOST, false);
    #$this->setopt(CURLOPT_HEADER, $this->getHeaders);
    $this->setopt(CURLOPT_NOBODY, !$this->getContent);
    $this->setopt(CURLOPT_COOKIEJAR, $this->fCookieFile);
    $this->setopt(CURLOPT_COOKIEFILE, $this->fCookieFile);
    $this->setopt(CURLOPT_USERAGENT, "MyCurl");
    $this->setopt(CURLOPT_POST, 1);
    $this->setopt(CURLOPT_CUSTOMREQUEST,'POST');
  #  if($fp)
  #    $this->setopt(CURLOPT_STDERR, $fp);
  }

  function destroy()
  {
    return curl_close($this->fSocket);
  }

  function head($url)
  {
    $this->init();
    if($this->fSocket)
    {
      $this->getHeaders = true;
      $this->getContent = false;
      $this->load_defaults();
      $this->setopt(CURLOPT_POST, 0);
      $this->setopt(CURLOPT_CUSTOMREQUEST,'HEAD');
      $this->setopt(CURLOPT_URL, $url);
      $result = curl_exec($this->fSocket);
      $this->destroy();
      return $result;
    }
    return 0;
  }

  function get($url)
  {
    $this->init();
    if($this->fSocket)
    {
      $this->load_defaults();
      $this->setopt(CURLOPT_POST, 0);
      $this->setopt(CURLOPT_CUSTOMREQUEST,'GET');
      $this->setopt(CURLOPT_URL, $url);
      $result = curl_exec($this->fSocket);
      $this->destroy();
      return $result;
    }
    return 0;
  }



  


function returnCode($buf) {
    list($response) = explode("\n", $buf, 2);
    if (preg_match("|^HTTP/\d.\d (\d+)|", $response, $matches)) {
        return 0+$matches[1];
    }
    return -1;
}

function dfputs($fp, $msg)
{
    return fputs($fp, $msg);
}

function sslToHost($host,$method,$path,$headers='',$data='',$useragent=0)
{
    $data .= "&accountType=HOSTED_OR_GOOGLE";
    if (empty($method)) {
        $method = 'GET';
    }
    $method = strtoupper($method);
    $fp = fsockopen("ssl://$host", 443, $errno, $err);
    if (FALSE === $fp) {
        debugg("Error $err opening socket!");
        exit(1);
    }

    if ($method == 'GET') {
        $path .= '?' . $data;
    }
    dfputs($fp, "$method $path HTTP/1.0\r\n");
    dfputs($fp, "Host: $host\r\n");
    dfputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
    dfputs($fp, "Content-length: " . strlen($data) . "\r\n");
    dfputs($fp, "accountType: HOSTED_OR_GOOGLE ");

    if (strlen($headers)) {
        dfputs($fp, "$headers\r\n");
    }

    if ($useragent) {
        dfputs($fp, "User-Agent: MSIE\r\n");
    }
    dfputs($fp, "Connection: close\r\n\r\n");
    if ($method == 'POST') {
        dfputs($fp, $data);
    }

    while (!feof($fp)) {
        $buf .= @fgets($fp,128);
    }
    fclose($fp);
    return $buf;
}


function sendToHost($host,$method,$path,$headers='',$data='',$useragent=0)
{
    if (empty($method)) {
        $method = 'GET';
    }
    $method = strtoupper($method);
    $fp = fsockopen($host, 80);
    if ($method == 'GET' && strlen($data)>0) {
        $path .= '?' . $data;
    }
    dfputs($fp, "$method $path HTTP/1.0\r\n");
    dfputs($fp, "Host: $host\r\n");
    if ($method == 'GET' && strlen($data)>0) {
        dfputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
    }
    dfputs($fp, "Content-length: " . strlen($data) . "\r\n");
    if (strlen($headers)) {
        dfputs($fp, "$headers\r\n");
    }

    if ($useragent) {
        dfputs($fp, "User-Agent: MSIE\r\n");
    }
    dfputs($fp, "Connection: close\r\n\r\n");
    if ($method == 'POST') {
        dfputs($fp, $data);
    }

    while (!feof($fp)) {
        $buf .= fgets($fp,128);
    }
    fclose($fp);
    return $buf;
}

function curlToHost($url,$method,$headers=array(''))
{
    ob_start();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
//    curl_setopt($ch,    CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,    CURLOPT_VERBOSE, 1); ########### debug
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); // follow redirects recursively


    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 16); // follow redirects recursively
    curl_setopt($ch, CURLOPT_TIMEOUT, 32); // follow redirects recursively

    $aa = curl_exec($ch);
    curl_close($ch);
    $ret = ob_get_contents(); 
    ob_end_clean();
    return $ret;
}
function getAuthCode($user, $pword, $service='cl')
{
    $buf=sslToHost('www.google.com',
        'post', 
        '/accounts/ClientLogin', 
        '', 
        'Email='.$user.'&Passwd='.$pword.'&service='.$service.'&source=phord-gcal.php-1&accountType=HOSTED_OR_GOOGLE');

    $code = returnCode($buf);
    if ($code === 200) {
        $lines=explode("\n", $buf);
        foreach ($lines as $line) {
            if (preg_match("/^Auth=(\S+)/i", $line, $matches)) {
                return $matches[1];
            }
        }
    }
    else
    {
        debugg("Error ($code) retrieving auth code");
    }    

}





  function compile_post_data($post_data)
  {
    $o="";
    if(!empty($post_data))
      foreach ($post_data as $k=>$v)
        $o.= $k."=".urlencode($v)."&";
    return substr($o,0,-1);
  }
  
  
   function read_header($ch, $header) { 
        $curl->headers[] = $header; 
        return strlen($header); 
        } 
  
}



?>