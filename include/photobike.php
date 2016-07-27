<?php

include_once('thumbnail-generator.php');

function photobike($photos, $photodir, $size=350, $class='photobike',$thumbdir='') {
  if(!is_array($photos)) $photos = array($photos);
  foreach($photos as $photo) {
    if($photo) {
      list($link, $desc) = explode('|',$photo,2);
      $photothumb[]       = "$link.jpg";
      $photolink[]        =  $link;
      $photodesc[$link]   =  str_replace(' ','_',preg_replace('/ *<.*?> */','_',$photo));
      $description[$link] =  $desc;
    }
  }
  $photothumb = generate_thumbnails($photothumb,$photodir, $size,$size);

  if($thumbdir=='') $thumbdir = "$photodir/thumbs/{$size}x{$size}";
  if(substr($photodir,0,7) === 'images/') { $photodir = substr($photodir,7,strlen($photodir)-7); }
  $content .= "<div id='photobike-outer'><div id='photobike' class='photobike'>\n";
  $content .= "<div class='comment'>Click Images to Enlarge</div>\n";
  foreach($photolink as $pos => $photo) {
    $desc = $description[$photo];
    list($img_width, $img_height, $img_type, $img_attr) = @getimagesize("$thumbdir/$photo.jpg");

    $imgalt = ($desc) ? preg_replace('/<.*?>/',' ',$desc) : $photo;
    $content .= "<div class='$class$pos'><a href='photo.php?$photodir/$photodesc[$photo]'><img src='$thumbdir/$photo.jpg' alt='$imgalt' width='$img_width' height='$img_height' />"; //
    if($desc) { $content .= "\n<span>$desc</span>\n"; }
    $content .="</a></div>\n";
  }
  $content .= "<div class='clear'>&nbsp;</div>\n";
  $content .= "<p class='back'><a href='#top'>Back to Top</a></p>";
  $content .= "</div></div>\n";
  $content .= "<div class='clear'>&nbsp;</div>\n";

  return $content;
}

function article_intro($article,$chars) {
  $next = '';
  $tag = false;
  $taglen = 0;
  for($len=0;$len<strlen($article) && ($len<$chars || $next!=' ' || $tag==true);$len++) {
    if($next == '<') $tag = true;
    if($next == '>') $tag = false;
    if($tag) $taglen++;
    $next = $article[$len];
  }
  $len += $taglen;
  $output = substr($article,0,$len);
  if($output && !preg_match('|</p>\s*$|',$output)) { $output .= '...</p>'; }
  return $output;
}


?>