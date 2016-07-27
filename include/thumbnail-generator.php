<?php
include_once('debug.php');
//generate_thumbnails_directory('../images/launch/800');

$server_root = str_replace('/home2','/home',dirname(dirname(__FILE__))).'/';
$file_path   = str_replace('/www.','/',     dirname($_SERVER['PATH_TRANSLATED'])).'/';
$subdir      = str_replace($server_root,'',$file_path);
$rootdir     = preg_replace('|[^/]*/|','../',$subdir);

function print_thumbnail_range($img_dir, $thumb_width, $thumb_height, $title_file='description.txt', $show_all_images=false) {

}

function print_thumbnail_gallery($image_array, $rows=null) {
  if($rows === null) {
    echo "<div class='thumnail_gallery' align='center'>";
    foreach($image_array as $image) {
      echo "<div><a href='$image[image]'><img src='$image[thumb]' title='$image[desc]' alt='$image[desc]'></img><br>$image[desc]</a></div>";
    }
    echo "</div>";
  } else {
    echo "<table class='thumnail_gallery' align='center'>";
    while(count($image_array)>0) {
      echo "<tr>";
      for($i=0; $i < $rows && count($image_array) > 0; $i++) {
        $image = array_shift($image_array);
        echo "<td><a href='$image[image]'><img src='$image[thumb]' title='$image[desc]' alt='$image[desc]'></img><br>$image[desc]</a></td>";
      }
      echo "</tr>";
    }
    echo "</tr></table>";
  }
}


function get_thumbnail_gallery($img_dir, $thumb_width, $thumb_height, $title_file='description.txt', $show_all_images=false) {
  $old_dir = `pwd`; chdir($img_dir);  // change to img_dir

  $title_array_temp = (is_array($title_file)) ? $title_file : file($title_file);
  $title_array = array();
  foreach($title_array_temp as $title) {
    $title = explode("=",$title,2); // filename = description
    foreach($title as $key => $value) { $title[$key] = trim($value); } // strip whitespace off ends
    if($title[0] == '')     continue; // no image specified, possibly a blank line
		if(in_array($title[0]{0},array('#',';','/'))) continue; // skip commented lines
    if($title[1] == '') { $title[1] = filename_to_title($title[0]); }
    $title_array[$title[0]] = $title[1];
  }

  if($show_all_images) { $thumb_array += image_files_in_dir('.'); }
  $thumb_array =  generate_thumbnails(array_keys($title_array), '.', $thumb_width, $thumb_height, 'thumbs/', 'tn_');

  // merge into a returnable array
  $return = array();
  foreach($title_array as $image => $desc) {
    $return[$image] = array('image'=>"$img_dir/$image", 'thumb'=>"$img_dir/$thumb_array[$image]", 'desc'=>$desc);
  }

  `cd $old_dir`; // return to original dir
  return $return;
}




function filename_to_title($filename) {
  $filename = preg_replace(array('/\..*$/','/\d/'),'',$filename); // remove extension and digits
  $filename = str_replace('_','',$filename);
  $filename = str_replace('  ',' ',$filename);
  $filename = ucwords($filename);
  $filename = htmlentities($filename);
  return $filename;
}

function image_files_in_dir($img_dir) {
  return explode("\n",`ls $img_dir -F | grep -v -e '[@/]\|^tn_' | grep -e '.jpg\|.JPG\|.jpeg\|.jpeg\|.png\|.PNG' | sed 's/*//'`);
}


function generate_thumbnails_directory($img_dir, $thumb_width = 150, $thumb_height = 150, $thumb_dir = 'thumbs/', $thumb_prefix = '') {
  //if($thumb_dir == 'thumbs/') $thumb_dir = "$GLOBALS[$rootdir]/images/thumbs/$img_dir";
  $file_array = image_files_in_dir($img_dir);
  foreach($file_array as $key => $value) { if($value == '') unset($file_array[$key]); } // strip blanks
  return generate_thumbnails($file_array, $img_dir, $thumb_width, $thumb_height, $thumb_dir, $thumb_prefix);
}

function generate_thumbnails($file_array, $img_dir, $thumb_width = 150, $thumb_height = 150, $thumb_dir = "thumbs/", $thumb_prefix = '') {
  //if($thumb_dir == 'thumbs/') $thumb_dir = "$GLOBALS[$rootdir]/images/thumbs/$img_dir";
  foreach($file_array as $key => $value) { if($value == '') unset($file_array[$key]); } // strip blanks
  $old_dir = trim(`pwd`); chdir($img_dir);  // change to img_dir
  $thumb_dir = str_replace('//','/',"$thumb_dir/{$thumb_width}x{$thumb_height}/");
  $thumb_url = str_replace('//','/',"$thumb_dir/$thumb_prefix");
  $note_file = str_replace('//','/',"$thumb_dir/thumb_size.txt");
  $only_newer = (strpos(`cat $note_file`,"$thumb_width x $thumb_height") === false) ? false : true;

  `mkdir -p  $thumb_dir`;
  `chmod 777 $thumb_dir/..`;
  `chmod 777 $thumb_dir`;
  `echo "$thumb_width x $thumb_height" > $note_file`;

  $return = array();
  foreach($file_array as $img_file) {
    $thumb_file = str_replace('//','/',"$thumb_dir/$thumb_prefix".$img_file);
    if(!file_exists($img_file)) {
      echo "<div style='background:white;'> cannot find: $img_dir/$img_file </div>\n";
      continue;
    }

    if($only_newer == false
    || !file_exists($thumb_file)
    || filemtime($thumb_file) < filemtime($img_file)    // is older than image file
    ) {
      createthumb($img_file,$thumb_file,$thumb_width,$thumb_height);
    }
    $return[$img_file] = $thumb_file;
  }

  chdir($old_dir);


  return $return;
}

/*
  Function createthumb($name,$filename,$new_w,$new_h)
  creates a resized image
  variables:
  $name     Original filename
  $filename Filename of the resized image
  $new_w    width of resized image
  $new_h    height of resized image
*/
function createthumb($name,$filename,$new_w,$new_h){
  global $gd2;

  $system = explode(".",$name);
  if (preg_match("/jpg|jpeg/",$system[1])) { $src_img = imagecreatefromjpeg($name); }
  if (preg_match("/png/",     $system[1])) { $src_img = imagecreatefrompng($name);  }
  $old_x = imageSX($src_img);
  $old_y = imageSY($src_img);
  if ($old_x > $old_y) {
    $thumb_w = $new_w;
    $thumb_h = $old_y*($new_h/$old_x);
  }
  if ($old_x < $old_y) {
    $thumb_w = $old_x*($new_w/$old_y);
    $thumb_h = $new_h;
  }
  if ($old_x == $old_y) {
    $thumb_w = $new_w;
    $thumb_h = $new_h;
  }

// //  code for GD v1
//  $dst_img = ImageCreate($thumb_w,$thumb_h);
//  imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

  $dst_img = ImageCreateTrueColor($thumb_w,$thumb_h);
  imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

  if (preg_match("/png/",$system[1])){
    imagepng($dst_img,$filename);
  } else {
    imagejpeg($dst_img,$filename);
  }
  `chmod 666 $filename`;
  imagedestroy($dst_img);
  imagedestroy($src_img);
//  echo "Generated photo $filename<br>";
}
?>
