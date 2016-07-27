<?php

$root_url = '';
$menu     = array();
$submenu  = array();
$submenu_title = '';

$menu = array(
  'Home'         => 'index.htm',
  'The Rider'    => 'photos.htm',
  'The Team'     => 'exec-summary.htm',
  'The Paddocks' => 'making-it-happen.htm',
  'People'       => 'commissions.htm',
);

$highlight[] = '';
$this_page = basename($_SERVER['PHP_SELF']);

function get_menu_multi($menu_array, $ul_tags='') {
  if($menu_array == null) return;
  $output = "\n<ul $ul_tags>\n";
  $keys = array_keys($menu_array);
  if(!$keys) print_r($keys);
  for($i=0;$i<count($keys);$i++) {
    $title = $keys[$i];
    $link  = $menu_array[$keys[$i]];
    $class = '';
    if($GLOBALS['this_page'] === $link) { $class = " class='uberlink'";}

    $output .= "<li$class><a href='$root_url$link'>$title </a>";
    if(is_array($menu_array[$keys[$i+1]])) {
      $output .= get_menu_multi($menu_array[$keys[$i+1]],"class='submenu'");
      $i++;
    }
    $output .= "</li>\n";
  }
  $output .= "</ul>\n";
  return $output;
}

function get_menu($menu_array, $ul_tags='') {
  if($menu_array == null) return;
  $output = array();
  foreach($menu_array as $entry) {
    if(is_array($entry))
      foreach($entry as $key => $value)
        $output[$key] = $value;
  }
  if($output)
    return get_menu_multi($output,$ul_tags);
  else
    return get_menu_multi($menu_array,$ul_tags);
}

function get_sub_menu($menu_array, $skip_top_level=true) {
  global $this_page,$submenu,$submenu_title;
  if($menu_array == null) return;
  $output = false;
  foreach($menu_array as $menu_title => $entry) {
    if(is_array($entry)) {
      if(get_sub_menu($entry,false) !== false) {
        $output  = $entry;
        $submenu = $entry;
        $submenu_title = str_replace('Array','',$menu_title);
        break;
      }
    } elseif(!$skip_top_level) {
      if($GLOBALS['this_page'] === $entry) {
        $output  = $menu_array;
        $submenu = $menu_array;
        $submenu_title = $menu_title;
        break;
      }
    }
  }
  return $output;
}



?>