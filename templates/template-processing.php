<?php

  include_once('menu.php');
//  include_once('debug/inc.debug.php');

  // set doctitle
  $doctitle = 'Francesca Giordano' . ( ($doctitle) ? " - $doctitle" :
                                      (($title)    ? " - $title"    : '' ));

  // show IE version in doctitle for debugging purposes
  if($_SERVER['SERVER_ADDR'] === '127.0.0.1' && $_SERVER['REMOTE_ADDR'] === '127.0.0.1'
  && preg_match('/MSIE [\d\.]+/',$_SERVER['HTTP_USER_AGENT'],$ie_version))
    $doctitle = "$ie_version[0] - " . $doctitle;

  // ensure arrays have been declared
  if(!is_array($quotes))        { $quotes     = ($quotes)     ? array($quotes)     : array(); }
  if(!is_array($stylesheet))    { $stylesheet = ($stylesheet) ? array($stylesheet) : array(); }
  if(!is_array($javascript))    { $javascript = ($javascript) ? array($javascript) : array(); }

  // remove empty values from the arrays
  foreach(array('quotes', 'stylesheet', 'javascript') as $var) {
    $$var = array_diff($$var, array(''));
  }

  if($template) {
    array_unshift($stylesheet,"francescagiordano-common.css");
    array_unshift($stylesheet,"francescagiordano-$template.css");
  } else {
    array_unshift($stylesheet,"francescagiordano.css");
  }
  foreach($stylesheet as $css) {
    $ie_only_css = str_replace(".css", "-ie-only.css", $css);
    $stylesheet_html .= "<link href='templates/$css' rel='stylesheet' type='text/css' media='all' />\n";
    $stylesheet_html .= "<!--[if IE]><link href='templates/$ie_only_css' rel='stylesheet' type='text/css' media='all' /><![endif]-->\n";
  }

  array_unshift($javascript,"dropdown.js");
  foreach($javascript as $js) {
    $javascript_html .= "<script language='javascript' type='text/javascript' src='include/$js'></script>\n";
  }

  // check which menus have been defined
//   if(!in_array("Menu", $menus)) { array_unshift($menus,"Menu"); }
//   if(!in_array("Admin", $menus)
//   &&  $admin_mode === true)     { array_unshift($menus,"Admin"); }
?>