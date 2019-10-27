<?php

// Usage : TS: http://iptv/stream/b0IxZmxxV2kvRzUyVU82ck9vQzhsQzUxTTBUaXNNL3hsNUUyc0dVZm1Ed3ByQjFVVzNQQkczczlKTms1NUZMLw==?authenticationtoken=admin
            //For Proxy: 
            //     http://iptv/stream/b0IxZmxxV2kvRzUyVU82ck9vQzhsQzUxTTBUaXNNL3hsNUUyc0dVZm1Ed3ByQjFVVzNQQkczczlKTms1NUZMLw==?authenticationtoken=admin&p=true
            //     or p=flase or p=V1crN2VEUTZJei9aNmFxMFo4VWlFOVY4aTh1QzQ3d0xocjEvRGEvTURVWHhCUlpkWENUU240Rk0yUFIyRHpRZA==
//          HLS: http://iptv/stream/b0IxZmxxV2kvRzUyVU82ck9vQzhsQzUxTTBUaXNNL3hsNUUyc0dVZm1Ed3ByQjFVVzNQQkczczlKTms1NUZMLw==/playlist.m3u8?authenticationtoken=admin
            //For Proxy: 
            //     http://iptv/stream/b0IxZmxxV2kvRzUyVU82ck9vQzhsQzUxTTBUaXNNL3hsNUUyc0dVZm1Ed3ByQjFVVzNQQkczczlKTms1NUZMLw==/playlist.m3u8?p=true&authenticationtoken=admin
error_reporting(E_ALL & ~E_WARNING);


$paramP = ['tree','false']; 
if(time() > @encrypt_decrypt('decrypt', getParam("authenticationtoken")) || ( in_array(getParam("p"), $paramP) ? false : strlen(@encrypt_decrypt('decrypt', getParam("p"))) < 5 ? true : false ) )
{
    Show404Error();
}

function Show404Error()
{
    header("HTTP/1.1 401 Unauthorized");
    echo "<h1>Unauthorized</h1>";
    echo "The page that you have requested could not be proceed.";
    exit();
}

function stream_encrypt_decrypt($action, $string)
{


  $output = false;
 
  //------------------ Encryption Setting --------------------

    date_default_timezone_set('UTC');
    $textToEncrypt = $string;
    $encryptionMethod = "AES-256-CBC";
    $secret = "im72charPasswordofdInitVectorStm"; //must be 32 char length
    $iv = substr($secret, 0, 16);


    //------------------End Encryption --------------------
 
  if ($action == 'encrypt')
  { 
    $output = $encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
    $output = str_replace("+","@",$output);
  }
  else
  {
    if ($action == 'decrypt')
    {
      $string = str_replace("@","+",$textToEncrypt);
      $output = openssl_decrypt($string, $encryptionMethod, $secret,0,$iv);
    }
  }
 
  return $output;
}

function encrypt_decrypt($action, $string)
{


  $output = false;
 
  //------------------ Encryption Setting --------------------

    date_default_timezone_set('UTC');
    $textToEncrypt = $string;
    $encryptionMethod = "AES-256-CBC";
    $secret = "im72charPasswordofdInitVectorStm"; //must be 32 char length
    $iv = substr($secret, 0, 16);


    //------------------End Encryption --------------------
 
  if ($action == 'encrypt')
  { 
    $output = $encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
    $output = str_replace("/","-_.",$output);
    $output = str_replace("+","@",$output);
  }
  else
  {
    if ($action == 'decrypt')
    {
      $string = str_replace("@","+",$textToEncrypt);
      $string = str_replace("-_.","/",$string);
      $output = openssl_decrypt($string, $encryptionMethod, $secret,0,$iv);
    }
  }
 
  return $output;
}

function ago($time) { 
    return (int)$timediff=time()-$time; 
}
function getFileSize($file_path) {
    clearstatcache();
    return filesize($file_path);
}
function getUrlData($url,$returnheader = false,$return = true ,$useragent=false,$referer=false,$headers=false,$proxy = false)
{


    // $stream = substr( str_replace(' ', '', $url) , 0, 4 ) === "http" ? explode('://', $url, 2)[1] : $url;
    // $proxy = 'https://as.mykhcdn.workers.dev/cdn/';//encrypt_decrypt('decrypt',getParam("p")) ;   //https://as.mykhcdn.workers.dev/cdn/:uri
    // $url = $proxy.rawurldecode($stream);   // https://as.mykhcdn.workers.dev/cdn/:encoded_uri
    echo $url;exit;

    if(getParam("useragent")!=false)
    {
        $useragent = rawurldecode(encrypt_decrypt('decrypt',getParam("useragent")));
    }
    else{
        $useragent ="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36";
    }
    if(getParam("referer"))
    {
        $referer = rawurldecode(encrypt_decrypt('decrypt',getParam("referer")));
    }
    if(getParam("proxy"))
    {
        $proxy = rawurldecode(encrypt_decrypt('decrypt',getParam("proxy")));
    }
    if(getParam("headers"))
    {
        $headers = rawurldecode(encrypt_decrypt('decrypt',getParam("headers")));
    }
    
    
    // GET the document
    // $doc = hQuery::fromUrl($url, ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );

    //Set UA
    if( $useragent != false)
    {
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    }
    //Set Referer
    if($referer != false)
    {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }   

    //Enabled Cookies
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    {
        curl_setopt($ch, CURLOPT_COOKIEJAR, "NULL");
    }
    else
    {
        curl_setopt($ch, CURLOPT_COOKIEJAR, "/dev/null");
    }

    //Custom Headers
    if($headers != false)
    {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $headers
        ));        
    }
    
    //Proxy
    if($proxy != false)
    {
        curl_easy_setopt(curl, CURLOPT_PROXY, $proxy);
        curl_easy_setopt(curl, CURLOPT_HTTPPROXYTUNNEL, 1);
        // curl_easy_setopt(curl, CURLOPT_SUPPRESS_CONNECT_HEADERS, 1);
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);

    if($returnheader == true){
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, true);
    
    }
    
    $data = curl_exec($ch);
    curl_close($ch);

    if($returnheader == true){
        
        $headers = $data;
        $data = [];

        $headers = explode(PHP_EOL, $headers);
        $data[0] = $headers[0];
        foreach ($headers as $row) {
            $parts = explode(':', $row);
            if (count($parts) === 2) {
                $data[trim($parts[0])] = trim($parts[1]);
            }
        }


    }

    
    if($return)
    {
        return $data;
    }
}

function getPlaylist($cdn,$url)
{
    $dir = "/tmp/$cdn";
    $trigger = "$dir/trigger.txt";
    $filename = "$dir/index.m3u8";
    $type = "application/vnd.apple.mpegurl";
    $id = "index.m3u8";
    $script = "/usr/share/segment.php";

    if(getParam("useragent")!=false)
    {
        $useragent = rawurldecode(encrypt_decrypt('decrypt',getParam("useragent")));
    }
    else{
        $useragent ="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36";
    }
    if(getParam("transcode") != false)
    {
        $transcode = rawurldecode(encrypt_decrypt('decrypt',getParam("transcode")));
    }
    if(getParam("referer"))
    {
        $referer = rawurldecode(encrypt_decrypt('decrypt',getParam("referer")));
    }
    if(getParam("proxy"))
    {
        $proxy = rawurldecode(encrypt_decrypt('decrypt',getParam("proxy")));
    }
    if(getParam("headers"))
    {
        $headers = rawurldecode(encrypt_decrypt('decrypt',getParam("headers")));
    }

    header("Content-Type: $type");
    header("Content-disposition: attachment; filename=\"".$id."\""); 
    if(file_exists($dir))
    {
        if (!file_exists($trigger))
            fclose($file = fopen($trigger, "w"));
    }
    
    if(!file_exists($trigger))
    {                
        $useragent = "-u '$useragent'";
        $referer = "-r '$referer'";
        $proxy = "-p '$proxy'";
        $transcode = "-t '$transcode'";
        $headers = "-h '$headers'";

        shell_exec("php $script -c '$cdn' -l '$url' $useragent $referer $proxy $transcode $headers > /dev/null 2>/dev/null &");
    }

    $start = time();
    while (ago($start)<120) {
        if(file_exists($filename))
        {                        
            break;
        }
    }
    if(!file_exists($filename))
    {
        flush();               // - Make sure all buffers are flushed
        //ob_flush();            // - Make sure all buffers are flushed
        exit;                  // - Prevent any more output from messing up the redirect
    }
    
    if(file_exists($trigger))
        unlink($trigger);
        
    //Output
    ob_start();
    readfile($filename);
    return $data = ob_get_clean();    
}

function checkPlaylist($playlist,$domainurl)
{
    
    $data = $playlist;
    $newstreamurl = false;

    $array = explode("\n", $data);
    $data = "";
    foreach($array as $item){
        $item = trim(preg_replace('/\s+/', ' ', $item));
        if(preg_match("[#]", $item))
        {
            if(preg_match("[audio_group]",$item) && preg_match("[ENG]",$item))
            {
                $item = str_ireplace("DEFAULT=NO","DEFAULT=YES",$item);
            }
            elseif(preg_match("[audio_group]",$item) && !preg_match("[ENG]",$item))
            {
                $item = str_ireplace("DEFAULT=YES","DEFAULT=NO",$item);
            }

        }
        elseif(!preg_match("[#]", $item))
        {
            if(strlen($item) >0 )
            {
                if(preg_match("[/]",$item[0]))
                {
                    $protocol = "http://";
                    if(preg_match("[https://]", $item))
                    {
                            $protocol = "https://";
                    }

                    $item = $protocol.explode("/", $domainurl)[2].$item;
                }
                if(!preg_match("[h]",$item[0]) && !preg_match("[t]",$item[1])&&!preg_match("[t]",$item[2]) && !preg_match("[p]",$item[3]))
                {
                    if(preg_match("[\?]",$item) )
                    {
                        $item = trim(preg_replace('/\s+/', ' ', $item))."&authenticationtoken=".getParam("authenticationtoken");
                    }
                    else
                    {
                        $item = trim(preg_replace('/\s+/', ' ', $item))."?authenticationtoken=".getParam("authenticationtoken");
                    }
                    if(getParam("p") != false)
                    {
                        $item = $item."&p=".getParam("p");
                    }

                } 

            }

        }
        
        $data .= $item."\n";
    }

    if(substr_count($data,"http") > 0)
    {
        $array = explode("\n", $data);
        foreach($array as $item){
            if(!preg_match("[#]", $item))
            {
                if(preg_match("[h]",$item[0])&&preg_match("[t]",$item[1])&&preg_match("[t]",$item[2])&preg_match("[p]",$item[3]))
                {
                    $newstreamurl = true;
                }

                break;
            }
            
        }
        
    }

    
    if($newstreamurl)
    {
        $array = explode("\n", $data);
        $data = "";
        $prefix = getUrl();
        foreach($array as $item){
            if( strlen($item) >0 && !preg_match("[#]", $item))
            {
                if(preg_match("[http]",$item))
                {
                    $script=str_replace(".php","",basename(__FILE__));
                    if(preg_match("[\.php]",$_SERVER["REQUEST_URI"]))
                    {
                        $script = basename(__FILE__);
                    }
                    $url = preg_replace('#[^/]*$#', '', curPageURL());
                    $url = explode($script,$url)[0].$script."/";
                    $item = trim(preg_replace('/\s+/', ' ', $item));
                    
                    if(preg_match("[\.m3u8|\.mdp]", $item))
                    {
                        $tmpname = explode("/", $item);
                        $tmpname = $tmpname[count($tmpname) - 1];

                        $exttype = ".m3u8";

                        if (substr_count($tmpname,".mpd") > 0) {
                            $exttype = ".mpd";
                        }
                        $streamparam = explode($exttype, $tmpname)[1];
                        $tmpname = explode($exttype, $tmpname)[0];
                        
                        $item = encrypt_decrypt('encrypt',$item)."/".$tmpname.$exttype.$streamparam;
                    }
                    else{
                        $item = encrypt_decrypt('encrypt',$item);
                    }
                    
                   
                    $item = $url.$item;
                    
                }
                if(preg_match("[\?]",$item) )
                {
                $item = trim(preg_replace('/\s+/', ' ', $item))."&authenticationtoken=".getParam("authenticationtoken");
                }
                else
                {
                    $item = trim(preg_replace('/\s+/', ' ', $item))."?authenticationtoken=".getParam("authenticationtoken");
                }
                if(getParam("p") != false)
                {
                    $item = $item."&p=".getParam("p");
                }    
                 
            }
            $data .= $item."\n";
        }
    }
    elseif(isParam())
    {
        
        $array = explode("\n", $data);
        $previousValue = null;
        $data = "";
        foreach($array as $item){
            if(!preg_match("[#]", $item))
            {
                if(preg_match("[#]", $previousValue))
                {
                    $url = preg_replace('#[^/]*$#', '', curPageURL());
                    $item = trim(preg_replace('/\s+/', ' ', $item));
                    if( preg_match("[/]",$url[-1]) )
                    {
                        if(preg_match("[/]",$item[0]))
                        {
                            $item = substr($item, 1);
                        }
                        elseif(preg_match("[\./]",$item))
                        {
                           $item = substr($item, 2);
                        }
                    }
                    else
                    {
                        if(preg_match("[\./]",$item))
                        {
                            $item = "/".substr($item, 2);
                        }
                        elseif(!preg_match("[/]",$item[0]))
                        {
                           $item = "/".$item;
                        }   
                    }
                    $item = $url.$item;
                }
            }
        $previousValue = $item;
        $data .= $item."\n";
        }
        
        
    }
    


    $length = strlen($data);
    header("Content-Length: $length");
    return $data;
}

function curPageURL() {
    $pageURL = 'http';
    if(isset($_SERVER["HTTPS"]))
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
function getChunkURI()
{
    $temp = explode("/", $_SERVER['REQUEST_URI'], 3)[2];

    if(!preg_match("[playlist.m3u8]",$_SERVER['REQUEST_URI']))
    {
        $tmp = substr($temp, strrpos($temp, '/') + 1);

        $script=str_replace(".php","",basename(__FILE__));
        if(preg_match("[\.php]",$_SERVER["REQUEST_URI"]))
        {
            $script = basename(__FILE__);
        }
        $ts = explode("/", explode($script,curPageURL())[1])[1];
        
        $tmp = substr(explode($ts, curPageURL())[1], 1);
        $tmp = str_replace("?authenticationtoken=","authenticationtoken=",$tmp);
        $tmp = str_replace("&authenticationtoken=","authenticationtoken=",$tmp);
        $tmp = explode("authenticationtoken=", $tmp)[0];
        return $tmp;
    }
    return false;
}
function getUrl()
{
    $script=str_replace(".php","",basename(__FILE__));
    if(preg_match("[\.php]",$_SERVER["REQUEST_URI"]))
    {
        $script = basename(__FILE__);
    }

    $temp = explode("/", explode($script,curPageURL())[1])[1];
    
    if(preg_match("[\?]",$temp[0]) )
    {
        $temp = substr($temp, 1);
    }
    $old = $temp;
    
    if(isParam())
    {
        $temp = explode("id=", $_SERVER["REQUEST_URI"])[1];
    }

    if(preg_match("[ts\?authenticationtoken]",$temp))
    {
        $temp = explode(".ts", $temp)[0];
    }

    if(preg_match("[\?authenticationtoken]",$temp))
    {
        $temp = explode("?authenticationtoken", $temp)[0];
    }
    
    $base64 ="";

    try{
        $base64 = encrypt_decrypt('decrypt',rawurldecode("$temp"));
        if(preg_match("[http|https]",$base64))
        {   
            $lastSlash = "/".substr($temp, strrpos($temp, '/') + 1);
            $base64 = str_replace("$lastSlash","",$temp) ;
            $temp = encrypt_decrypt('decrypt',rawurldecode("$base64"));
        }
    }
    catch(Exception $e){
        $base64 ="";
    }

    
    $url = $temp;
    if( !preg_match("[ts\?authenticationtoken]",$old)  && preg_match("[\.m3u8|\.ts]",$_SERVER["REQUEST_URI"]) && !preg_match("[playlist.m3u8]",$_SERVER["REQUEST_URI"]))
    {
        $lastSlash = substr($url, strrpos($url, '/') + 1);
        $chunk = getChunkURI();
        // if(preg_match("[\?]",$lastSlash)&& preg_match("[\?]",$chunk))
        // {
        //     $url = str_replace($lastSlash,$chunk,$url);
        // }
        // elseif(preg_match("[\?]",$lastSlash)&& !preg_match("[\?]",$chunk))
        // {
        //     $url = str_replace(explode("?",$lastSlash)[0],$chunk,$url);
        // }
        // else 
        // {
        //     $url = str_replace($lastSlash,$chunk,$url);
        // }

        $url = str_replace($lastSlash,$chunk,$url);
        
    }   

    return $url;
}

function isParam()
{
    if(isset($_GET["id"]))
    {
        return true;
    }
    return false;
}

function getParam($input){
    $string = $input."=";
    $tmp = urldecode($_SERVER["REQUEST_URI"]);
    if(!preg_match("[$string]", $tmp))
    {
        return false;
    }
    $temp = explode($string, $tmp)[1];
    $temp = explode("&", $temp)[0];
    if(preg_match("[url=]",$temp))
    {
        $temp = explode("/", $temp)[0];
    }
    
    return $temp;
}

function get_http_response_code($domain1) {
    $headers = @get_headers($domain1);
    return substr($headers[0], 9, 3);
}

$url = getUrl();

$id = substr($url, strrpos($url, '/') + 1);
$id = preg_replace('#\?[^?]*$#', '', $id);
$cdn = getParam("cdn");
$type = "";
$length = 0;
$useragent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36";
if(getParam("useragent")!=false)
{
    $useragent = rawurldecode(encrypt_decrypt('decrypt',getParam("useragent")));
    
}
// echo $url;exit;
ini_set('user_agent', $useragent);

$script=str_replace(".php","",basename(__FILE__));
if(preg_match("[\.php]",$_SERVER["REQUEST_URI"]))
{
    $script = basename(__FILE__);
}

$ts = explode("/", explode($script,curPageURL())[1])[1];

if(preg_match("[ts\?authenticationtoken]",$ts))
{
    $type = "video/MP2T";
    header("Content-Type: $type");
    header("Location: $url",true, 302);

    flush();               // - Make sure all buffers are flushed
    //ob_flush();            // - Make sure all buffers are flushed
    exit;                  // - Prevent any more output from messing up the redirect
}
elseif(preg_match("[\.ts]",$url))
{
    
    $type = "video/MP2T";
    header("Content-Type: $type");
    $old = $url;

    $url = "https://d2dbd9ow79oetv.cloudfront.net/$url";
    if(getParam("p")=="tree")
    {
        header("Location: $url",true, 302);
    }
    elseif (getParam('p')=='false') {
        $script=str_replace(".php","",basename(__FILE__));
        if(preg_match("[\.php]",$_SERVER["REQUEST_URI"]))
        {
            $script = basename(__FILE__);
        }
        $head = explode($script,curPageURL())[0];
        $stream = "/".$script."/".encrypt_decrypt('encrypt',$old).".ts?authenticationtoken=".getParam("authenticationtoken");
        header("Location: $old",true, 302);
    }
    elseif(getParam("p")!=false)
    {

        $stream = substr( str_replace(' ', '', $old) , 0, 4 ) === "http" ? explode('://', $old, 2)[1] : $old;
        $proxy = encrypt_decrypt('decrypt',getParam("p")) ;   //https://as.mykhcdn.workers.dev/cdn/:uri
        $url = $proxy.stream_encrypt_decrypt('encrypt',rawurldecode($stream));   // https://as.mykhcdn.workers.dev/cdn/:encoded_uri
        header("Location: $url",true, 302);
    }
    else
    {
        $statuscode = get_http_response_code($url);
        if($statuscode>400)
        {
            $script=str_replace(".php","",basename(__FILE__));
            if(preg_match("[\.php]",$_SERVER["REQUEST_URI"]))
            {
                $script = basename(__FILE__);
            }
            $head = explode($script,curPageURL())[0];
            $stream = "/".$script."/".encrypt_decrypt('encrypt',$old).".ts?authenticationtoken=".getParam("authenticationtoken");
            header("Location: $stream",true, 302);

        }
        else
        {
            $url = urlencode(str_replace("=","",base64_encode(rawurldecode("$url")))) ;
            $url = "https://d2dbd9ow79oetv.cloudfront.net/proxy.maintenis.com/index.php?q=$url&hl=8";//2e9, e9, e0
            header("Location: $url",true, 302);
        }
    }

    flush();               // - Make sure all buffers are flushed
    //ob_flush();            // - Make sure all buffers are flushed
    exit;                  // - Prevent any more output from messing up the redirect
}

if($cdn != false && preg_match("[chunklist_$cdn]",$url))
{
    $url = "/tmp/$cdn/$id";
    if(file_exists($url))
    {
        $type = "video/MP2T";
        $length = getFileSize($url);
        
        http_response_code(200);
        header("Content-Type: $type");
        header("Content-Length: $length");
    }
    else
    {
        http_response_code(404);
        flush();               // - Make sure all buffers are flushed
        //ob_flush();            // - Make sure all buffers are flushed
        exit;                  // - Prevent any more output from messing up the redirect
    }
    
}
elseif(preg_match("[rtmp://|udp://|rtsp://]",strtolower($url)) && $cdn != false)
{
    
    $type = "video/MP2T";
    $length = 0;
}
else
{

    
    if(getParam("referer") || getParam("useragent")){
        $file_info = getUrlData($url,true);
    }else{
        $file_info = @get_headers($url,1);
    }

    $file_info = array_change_key_case($file_info,CASE_LOWER);

    if(!$file_info[0] || $file_info[0] === null || $file_info[0]==="")
    {
        http_response_code(404);
        die;
    }
    elseif (preg_match("[301|302|303|307|308]", $file_info[0])) {

        if(preg_match("[http]", $file_info['location']))
        {
            $url = $file_info['location'];
        }
        else{

            $protocol = explode("://", $url)[0].'://';
            $host = explode('/', explode("://", $url)[1])[0].'/';
            $url = $protocol.$host;
            $item = $file_info['location'];
            if( preg_match("[/]",$url[-1]) )
            {
                if(preg_match("[/]",$item[0]))
                {
                    $item = substr($item, 1);
                }
                elseif(preg_match("[\./]",$item))
                {
                   $item = substr($item, 2);
                }
            }
            else
            {
                if(preg_match("[\./]",$item))
                {
                    $item = "/".substr($item, 2);
                }
                elseif(!preg_match("[/]",$item[0]))
                {
                   $item = "/".$item;
                }   
            }
            $url = $url.$item;
        }
        
    }
    else{
        header($file_info[0]);
    }
    
    if(isset($file_info['content-type']))
    {
        if(gettype($file_info['content-type'])=="array")
        {
            $type = strtolower($file_info['content-type'][count($file_info['content-type'])-1]);
        }
        else
        {
            $type = strtolower($file_info['content-type']);
        }
        
    }
    
    if(isset($file_info['content-length']))
    {
        $length = strtolower($file_info['content-length']);
    }
    
    
    
}

if(strtolower($type).include('video')){
    header("Cache-Control: s-maxage=1, stale-while-revalidate");
}

header("Access-Control-Allow-Origin: *");

if($length != 0 )
{
    if(!(isParam() && preg_match("[mpegurl]", $type)) && !getParam("cdn") )
    {
        header("Content-Length: $length");
    }
    
}

if(!getParam("cdn"))
{
    header("Content-Type: $type");
}


if (!preg_match("[text|html|json|plain]", $type) && isParam())
{

    header("Content-disposition: attachment; filename=\"".$id."\""); 
}

// Can be Done by using Rewrite
if(preg_match("[mpegurl]", $type) || preg_match("[\.m3u8]",$url))
{
    $playlist = "";
    if(getParam("cdn") != false)
    {   
        set_time_limit(120);
        $playlist = getPlaylist($cdn,$url);

    }
    else
    {

        $playlist = getUrlData($url);
      
    }

    echo checkPlaylist($playlist,$url);
    flush();               // - Make sure all buffers are flushed
    //ob_flush();            // - Make sure all buffers are flushed
    exit;                  // - Prevent any more output from messing up the redirect

}
else
{

    if(preg_match("[text|html|json|plain]", $type))
    {

		echo getUrlData($url);
    }
    else
    {
        header("Accept-Ranges: bytes");
        // flush();
        set_time_limit(0);
        if($length != 0)
        {
            if(preg_match("[/tmp/]",$url))
            {
                readfile($url);
            }
            {

                getUrlData($url,false,$return=false);
                
            }

        }
        else
        {
            if(getParam("cdn") != false)
            {   
                
                $playlist = getPlaylist($cdn,$url);
                echo checkPlaylist($playlist,$url);
            }
            else
            {
                // Passthrough
                    // readfile($url);
                // ob_end_flush();
                // $fp = fopen($url,'rb');
                // fpassthru($fp);

                
                $id = "?timestamp=".date('mdH').substr(date('i'),0,1);
                if(preg_match("[\?]",$url) )
                {
                    $id = str_replace("?","&",$id);
                }

                $url = $url.$id;
                $old = $url;
                $url = "https://d2dbd9ow79oetv.cloudfront.net/$url";


                if(getParam("p")=="tree")
                {
                    header("Location: $url",true, 302);
                }
                elseif (getParam('p')=='false') {
                    $script=str_replace(".php","",basename(__FILE__));
                    if(preg_match("[\.php]",$_SERVER["REQUEST_URI"]))
                    {
                        $script = basename(__FILE__);
                    }
                    $head = explode($script,curPageURL())[0];
                    $stream = "/".$script."/".encrypt_decrypt('encrypt',$old).".ts?authenticationtoken=".getParam("authenticationtoken");
                    header("Location: $old",true, 302);
                }
                elseif(getParam("p")!=false)
                {

                    $stream = substr( str_replace(' ', '', $old) , 0, 4 ) === "http" ? explode('://', $old, 2)[1] : $old;
                    $proxy = encrypt_decrypt('decrypt',getParam("p")) ;   //https://as.mykhcdn.workers.dev/cdn/:uri
                    $url = $proxy.stream_encrypt_decrypt('encrypt',rawurldecode($stream));   // https://as.mykhcdn.workers.dev/cdn/:encoded_uri
                    header("Location: $url",true, 302);
                }
                else
                {
                    $statuscode = get_http_response_code($url);
                    if($statuscode>400)
                    {
                        $script=str_replace(".php","",basename(__FILE__));
                        if(preg_match("[\.php]",$_SERVER["REQUEST_URI"]))
                        {
                            $script = basename(__FILE__);
                        }
                        $head = explode($script,curPageURL())[0];
                        $stream = "/".$script."/".encrypt_decrypt('encrypt',$old).".ts?authenticationtoken=".getParam("authenticationtoken");
                        header("Location: $stream",true, 302);

                    }
                    else
                    {
                        $url = urlencode(str_replace("=","",base64_encode(rawurldecode("$url")))) ;
                        $url = "https://d2dbd9ow79oetv.cloudfront.net/proxy.maintenis.com/index.php?q=$url&hl=8";//2e9, e9, e0
                        header("Location: $url",true, 302);
                    }
                }



            }
            
        }
        
    }
}
flush();               // - Make sure all buffers are flushed
//ob_flush();            // - Make sure all buffers are flushed
exit;                  // - Prevent any more output from messing up the redirect

?>