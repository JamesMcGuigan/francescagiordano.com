<?php

#debug('realpath(\'worldfuturecouncil-new.php\')',realpath('templates/worldfuturecouncil-new.php'));
/*
  $cache_depend[] = realpath('templates/worldfuturecouncil-new.php');
  $cache_depend[] = realpath('templates/worldfuturecouncil-new2.php');
  $cache_depend[] = realpath('templates/worldfuturecouncil-old.php');
  $cache_depend[] = realpath('templates/worldfuturecouncil-popup.php');

  include('inc/cache.php');
  if(check_cache($cache_depend)) exit;
*/

  ob_start("ob_gzhandler");

  $template  = '';
  $templates = array('popup');
  foreach($templates as $value) {
    if($_SERVER['QUERY_STRING'] == $value) { $template = $value; break; }
  }
  if($template == '') {
  foreach($templates as $value) {
    if($_COOKIE['template']     == $value) { $template = $value; break; }
  }}

  if($template != ''
  && $template != 'popup'
  && $template != $_COOKIE['template']) {
    if(!headers_sent()) { setcookie('template',$template); }
  }

  if($template == '') $template = 'template';
  switch($template) {
    default: include_once("francescagiordano-$template.php"); break;
  }

  //write_cache();
?>