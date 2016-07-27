<?php

$target_blank = 'target="_blank"';

function html_combo($options, $field=null, $selected=null, $tags=null) {

  echo "<select name='$field' $tags>";
  foreach($options as $option_value => $option) {
    // numberic indexs mean value not specified, use same as display
    if(is_string($option_value)==false) { $option_value = $option; }

    $option_value = str_replace("'",'&#039;',$option_value);
    $select_tag = ($option == $selected) ? 'selected="selected"' : '';
    echo "<option value='$option_value' $select_tag>$option</option>";
  }
  echo "</select>\n";
}

function text_decorate($text) {
  $start  = '%(\s[\*_/><]*)';
  $end    =    "([\*_/><]*\s)%Us";
  $middle = "((?![^\S]).*(?![^\S]))";
  $patterns = array(
    '/&quot;.*&quot;/Us'          => '<em>$0</em>', // "Italics"
    $start.'"' .$middle.'"' .$end => '$1<em>&quot;$2&quot;</em>$3', // "Italics"

    //'/([ ,.!>-])(\d+)C([ <,.!-])/' => '$1$2&#176;C$3', // add degree sign after 2C
  );
  $text = preg_replace(array_keys($patterns),$patterns,$text);
  return $text;
}


function text_format($text,$is_text_wrapped=true) {

	// HTML comment to include raw html
	if(stristr($text, '<!-- raw html -->') !== false) return $text;

	include_once('include/inc.validate.php');

  // remove starting and trailing whitespace and blank lines
  $text = preg_replace('/^[\s\n]*/s' ,'',$text);
  $text = preg_replace( '/[\s\n]*$/s','',$text);

  // place into paragraph array and add spaces to both sides
  if($is_text_wrapped == true) {
    // strip out lines with only whitespace chars and reduce multiple blank lines to one
    $text = preg_replace('/^(\s)*$/m','',$text);
    $paragraphs = explode("\n\n",$text);
    foreach($paragraphs as $key => $value) { $paragraphs[$key] = ' '.$value.' '; }
  } else {
    $paragraphs = array(' '.str_replace("\n"," <br/>\n ",$text).' '); // extra spaces are needed for pattern matching
  }

  $start  = '%(\s[\*_/><]*)';
  $end    =    "([\*_/><]*\s)%U";
  $middle = "((?![^\S]).*(?![^\S]))";
  $m1 = '((?![^\S])(.(?![';
  $m2 =                  ']{2}))*[\S]*(?![^\S]))';
  $patterns = array(
    $start.'##'.$middle.'##'.$end => '$1<h2 style="text-align:center">$2</h2>$3', // ##Header##
    $start.'\*'.$middle.'\*'.$end => '$1<b>$2</b>$3',                             // *Bold*
    $start.'_' .$middle.'_' .$end => '$1<u>$2</u>$3',                             // _Underline_
    $start.'//'.$middle.'//'.$end => '$1<i>$2</i>$3',                             // //Italics//
    $start.'"' .$middle.'"' .$end => '$1<i>"$2"</i>$3',                           // "Italics"

    $start.'>>'.$m1.'<'.$m2.'>>'.$end => '$1<div style="text-align:right;">$2</div>$3',  // >>AlignRight>>
    $start.'<<'.$m1.'>'.$m2.'<<'.$end => '$1<div style="text-align:left">$2</div>$3',    // <<AlignLeft<<
    $start.'>>'.$m1.'>'.$m2.'<<'.$end => '$1<div style="text-align:center">$2</div>$3',  // >>Center<<
    $start.'<<'.$m1.'<'.$m2.'>>'.$end => '$1<div style="text-align:justify">$2</div>$3', // <<Justify>>

    '%^\s*#[^#](.*)$%' => '<ul><li>$1</li></ul>',
    '/\s+&\s+/' => ' &amp; '
  );
  // run formatting on paragraphs;
  $paragraphs = preg_replace(array_keys($patterns),$patterns,$paragraphs);

  // locate and a href emails and links
  foreach($paragraphs as $key => $value) {
    $paragraphs[$key] = highlight_emails($paragraphs[$key]);
    $paragraphs[$key] = highlight_links ($paragraphs[$key]);
  }

  foreach($paragraphs as $key => $value) {
    //$html .= " <p><div>".$paragraphs[$key]."</div></p>\n";
    $html .= " <p>".$paragraphs[$key]."</p>\n";
  }
  // merge consecutive bullet points into single <ul>
  $html = preg_replace('%</ul>(</?p>|</?div>|\s+)*<ul>%s',"\n",$html);
  $html = preg_replace('%<p>\s*<ul>(.*?)</ul>\s*</p>%s',"<ul>$1</ul>",$html);

  return $html;
}

// scans the text and
function highlight_emails($text) {
  if(strpos($text,'@') === false) { return $text; } // quick test for emails

  $email_reg = "%([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})(?!\S)%";
  preg_match_all($email_reg,' '.$text.' ',$find);
  foreach($find[0] as $email) {
    list($username,$domaintld) = split("@",$email);
    if(getmxrr($domaintld,$mxrecords)) { // check its from a valid email domain
      $encoded = email_encode($email);
      $ahref = "<a href='mailto:$encoded'>$encoded</a>";
      $text = str_replace($email,$ahref,$text);
    }
  }
  return $text;
}

function highlight_links($text) {
  global $target_blank;

  $link_reg = '%\s((http://|https://ftp://|www.)\S*)(?=\s)%';
  preg_match_all($link_reg,' '.$text.' ',$find);
  for($i=0;$i<count($find[1]);$i++) {
    $link = $find[1][$i]; // find[1] is find[0] without starting space
    $href = (strpos($link,'://') === false) ? 'http://'.$link : $link;
    if(validate_url($href) == true) { // check its a valid url
      $ahref = " <a href='$href' $target_blank >$link</a> ";
      $text = str_replace($find[0][$i],$ahref,$text); // need the starting space to avoid <a href='<a href=''></a>'></a>
    }
  }
  return $text;
}


// james@starsfaq.com =
// &#106;&#097;&#109;&#101;&#115;&#064;&#115;&#116;&#097;&#114;&#115;&#102;&#097;&#113;&#046;&#099;&#111;&#109;
function email_encode($email) {
  for($i=0;$i<strlen($email);$i++) {
    $decimal = hexdec(bin2hex($email{$i}));
    $encoded .= '&#'.str_pad($decimal, 3, '0', STR_PAD_LEFT).';';
  }
  return $encoded;
}


?>