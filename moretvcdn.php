<?php
$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Origin: pili-live-hdl.qhmywl.com\r\n" .
              "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
  )
);

$context = stream_context_create($options);

// Open the file using the HTTP headers set above

$id = $_GET['id'];
$uri = "http://pili-live-hdl.qhmywl.com/dsdtv/".$id.".m3u8";
try{
    if (empty($id) || !isset($id)) {
      throw new Exception("The field is undefined."); 
    }
    $proxy1 = "http://cors-any.fotor.com.cn/";
    $proxy2 = "http://cors.awebman.com/";
    $file = @file_get_contents($proxy1.$uri, false, $context);

    if (getHttpCode($http_response_header) != 200) {
      throw new Exception("The field is undefined."); 
    }
}
catch(Exception $e){

    header("HTTP/1.1 401 Unauthorized");
    echo "<h1>Unauthorized</h1>";
    echo "The page that you have requested could not be proceed.";
    exit();

}
if(strpos($file, 'http') !== false){
    $url ="http" . explode("http",$file)[1];
    header("Location: $url");
    die();
}else {
    http_response_code(400);
    echo("<h1></h1>");
}

function getHttpCode($http_response_header)
{
    if(is_array($http_response_header))
    {
        $parts=explode(' ',$http_response_header[0]);
        if(count($parts)>1) //HTTP/1.0 <code> <text>
            return intval($parts[1]); //Get code
    }
    return 0;
}

?>