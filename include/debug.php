<?php

//if($_GET['debug'] == 'true'){ include_once('../Gubed/Gubed.php'); }

function print_r_html($myvar){
	echo '<pre>'.str_replace(array("\n" , " "), array('<br>', '&nbsp;'), print_r($myvar, true)).'</pre>';
}

function debug ($desc, $var) {
  if(true || $_GET['vars']==='show') {
		echo "<b>DEBUG:</b> $desc = <b>";
		if(is_array($var))      print_r_html($var);
		elseif(is_object($var)) print_r_html($var);
		else                    var_dump($var);
		echo "</b><br>";
  }
}

?>