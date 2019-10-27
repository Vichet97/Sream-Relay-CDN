<?php

// if(isset($_GET['id']))
// {
//     $id = $_GET['id'];
//     include("http://iptv/live/");
// }
// else
// {
//     echo "<h1>404 Not Found</h1>";
//     echo "The page that you have requested could not be found.";
//     exit();
// }

// <!-- http://peepark21.synology.me/restream.php?url=http://115.178.24.80:1935/dvr/_definst_/HangmeasHD.stream/playlist.m3u8 -->
// <!-- http://meepark21.duckdns.org:5004//lineup.json  tvhproxy   /rss/cdn.php | /restream.php-->   user:meepark21:park1730   meepark21@gmail.com  CJONE or Tving meepark21:pa17301730
// <!-- http://180.66.106.93/torrent/cdn.php?url= ryu8170 <-@gmail.com  pass: lim545054! minibok.synology.me MininasII has proxy-->  
// <!-- SK BTV Mobile LG U+ -->
// mempisto@gmail.com encarta.myqnapcloud.com  http://49.172.145.124:8083  encarta   mempas11!
// cookies
// post/get


// ffmpeg -i http://meepark21:park1730@meepark21.duckdns.org:9981/stream/channel/2273432d3a9fa958f819471530351a1c?profile=webtv-vp8-vorbis-webm \
// -c:v copy -c:a aac \
// -strict -2 \
// -strftime 1  -use_localtime 1 -g 90 \
// -hls_time 10 \
// -hls_list_size 3 \
// -hls_flags delete_segments \
// -hls_segment_filename /tmp/task/%m%d%H%M%S.ts \
// -f hls /tmp/task/index.m3u8


// $id = $_GET["id"];
// $auth = $_GET["auth"];
// $time = $_GET["time"];
// $secretValue = "secret12345";
// $timeLimit = time() - 3600; /* 1 hour */

// if (isset($auth) && isset($time)) {
//    /* check token */
//    $hashedValue = sha1($time . $secretValue);
//    if ($hashedValue != $auth || $time < $timeLimit) {
//       echo "Token not valid";
//    }
// } else {
//    /* make new token */
//    $timeValue = time();
//    $hashedValue = sha1($timeValue . $secretValue);

//    /* redirect to new link */
//    http_response_code(302); /* redirect */
//    header("Location: http://example.com/stream.php?id=" . $id . "&time=" . $timeValue . "&auth=" . $hashedValue);
// }
function getUrl()
{
    $temp = explode("input=", $_SERVER["REQUEST_URI"])[1];
    return rawurldecode($temp);
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
// http://103.47.132.164/PLTV/88888888/224/3221227035/index.m3u8
// echo "http://api.ipify.org?authenticationtoken="."\n<br>";
// $a = encrypt_decrypt('encrypt',);
// echo $a;



echo rawurldecode(encrypt_decrypt('decrypt',getUrl())) ;
// // echo substr("testers", 1);
// echo "testers"[-1];

?>