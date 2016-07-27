<?php

$imagedir = 'images';
list($photo, $desc) = explode('|',$_SERVER['QUERY_STRING'],2);
$photo  = "images/$photo.jpg";
if($_SERVER['QUERY_STRING']
&& strpos($photo,'../') !== false
&& file_exists($photo)) { $title = 'Unknown Photo'; }
else {

$imgalt = $imgalt = ($desc) ? $desc : $photo;
if($desc) $desc = "<span>".str_replace('_',' ',urldecode($desc))."</span>";
list($img_width, $img_height, $img_type, $img_attr) = @getimagesize($photo);
$content .= "<div class='bigphoto'><img src='$photo' alt='$imgalt' width='$img_width' height='$img_height' />$desc</div>";

  if(strpos($_SERVER['HTTP_REFERER'], 'ebay')) { $referer = $_SERVER['HTTP_REFERER']; } else {
    $referer = basename($_SERVER['HTTP_REFERER']);
    if(!$referer
    || !strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])       // is referer from this this website
    || !file_exists(preg_replace('/^([^?]*).*$/','$1',$referer)) ) { // remove query string and look for file
      $referer = basename(dirname($photo)).'.htm';
      if(!file_exists($referer)) {
        $dh  = opendir('./');
        while (($filename = readdir($dh)) !== false) {
          if(strpos($filename,$referer) !== false) { $referer = $filename; break; }
        }
      }
    }
  }
$content .= "<p class='back'><a href='$referer'>Back to Previous Page</a></p>";
}

include_once('templates/francescagiordano.php');

?>