<?php

include_once('data/articles.php');
include_once('include/debug.php');

$imagedir = 'images';
if(!$_GET['img']) $_GET['img'] = $_SERVER['QUERY_STRING'];
$request = $_GET['img'];
$photo   = $_GET['img'];
$zoom    =($_GET['zoom'] ? $_GET['zoom'] : 100);

// // find referer
// $referer = basename($_SERVER['HTTP_REFERER']);
// if(!$referer
// || !strpos('articles.htm',$_SERVER['HTTP_REFERER'])              // is referer from articles.htm
// || !strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])       // is referer from this this website
// || !file_exists(preg_replace('/^([^?]*).*$/','$1',$referer)) ) { // remove query string and look for file
//   $referer = "articles.htm";
// }

// locate image data within articles array
$article = array();
foreach($articles as $lang => $article_lang) { foreach($articles[$lang] as $article) {
    if($article['link'] === $request) {
      $articledir = $articles_photodir[$lang];
      break 2;
    }
}}  if($article['link'] !== $request) {
  $article_text[$request] = '';
  $title = "Cannot find image";
  include_once('templates/francescagiordano.php');
  return;
}
extract($article);

// generate zoom in/out bar
$this_page = preg_replace('/&?zoom=[^&]*/','',$_SERVER['REQUEST_URI']);
$zoom_link = "<strong>Click to change Zoom Level: ($zoom%)</strong><br/>";
foreach(array(25,50,75,100,125,150,175,200,225,250,300,350,400) as $zoom_level) {
  if($zoom_level == $zoom) $zoom_link .= "<strong>";
  $zoom_link .= "<a href='$this_page&zoom=$zoom_level'>$zoom_level%</a>\n";
  if($zoom_level == $zoom) $zoom_link .= "</strong>";
}

$doctitle = implode(' - ',array($headline,$paper,$date));
if($article_text[$link]) {
  $back_link = ($_GET['from']) ? "<a href='articles.htm?article=$link&from=$_GET[from]'>Back to Article Text</a>"
                               : "<a href='articles.htm?$link'>Back to Article Text</a>";
} else {
  $back_link = ($_GET['from']) ? "<a href='articles.htm?$_GET[from]#$link'>Back to Article List</a>"
                               : "<a href='articles.htm?$lang#$link'>Back to Article List</a>";
}
$back_link = "<strong>$back_link</strong><br/>\n";

list($img_width, $img_height, $img_type, $img_attr) = @getimagesize("$articledir/$photo.jpg");
$img_width  = $img_width  * $zoom / 100;
$img_height = $img_height * $zoom / 100;
$image = "<div><img src='$articledir/$photo.jpg' alt='$doctitle' width='$img_width' height='$img_height' />$desc</div>";

ob_start("ob_gzhandler");
echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//dtd xhtml 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/dtd/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>$doctitle</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<style type='text/css'>
a { color:#551a8b; color:#df7800; font-weight:bold; }
</style>
</head>
<body>
$zoom_link
<br/><br/>
$image
<br/>
$back_link
</body>
</html>
HTML;
?>