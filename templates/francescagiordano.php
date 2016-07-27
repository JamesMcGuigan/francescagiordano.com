<?php //print $content; exit; ?>
<?php if($dont_print_template == false) { ?>
<?php //include_once('../Gubed/Gubed.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//dtd xhtml 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/dtd/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<?php
//ob_start("ob_gzhandler");
include_once('template-processing.php');
@include_once('include/html-functions.php');
@include_once('../include/html-functions.php');
echo <<<HTML
<title>$doctitle</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
$stylesheet_html
$javascript_html
HTML;

if($inline_style) { echo "\n<style type='text/css'>\n$inline_style\n</style>\n"; }

?>
</head>
<body>
<a name='top'></a>
<div id='body'>
<div id='pageheader'>
<h1 id='pagetitle'><strong>F</strong>rancesca <strong>R</strong>omana<br/> <strong>G</strong>iordano</h1>
<h2 id='pagesubtitle'>Racing Activity</h2>

<table width='100%' cellpadding='0' cellspacing='0'><tr>
<td width='4%'>&nbsp;</td>
<td width='179'><img src='images/template/header-francesca.jpg' width='179' height='125' border='0' alt='[Francesca Romana Giordano]' /></td>
<td width='50%'>&nbsp;</td>
<td width='190' valign='bottom'><img src='images/template/header-bike.jpg' width='190' height='125' border='0' alt='[Francesca on her Bike]'/></td>
<!--<td width='1%'></td>-->
</tr></table>
</div> <!-- end pageheader -->

<?php
  $sub = ($subtitle) ? 'with-subtitle':'';
                echo "<div class='title'>\n";
                echo "<div id='trackround'></div>\n";
//  if($title)    echo "<h2 class='title $sub'>$title</h2>\n";
//  if($subtitle) echo "<h3 class='subtitle'>$subtitle</h3>\n";
                echo "</div>\n\n";
?>

<?php
$menu_photodir = 'images/menu/';
$menus = array(
array('link'     => 'articles.htm',
      'title'    => 'Home',
     ),
array('link'     => 'articles.htm',
      'title'    => 'Articles',
      'photo'    => 'headlines.jpg',
      'photoalt' => 'Articles',
      'submenu'  => array(
          'English Articles'   => 'articles.htm?english',
          'Italian Articles'   => 'articles.htm?italian',
          'by Francesca'       => 'articles.htm?byfrancesca',
          'ADM Articles'       => 'articles.htm?adm',
          )
     ),
array('link'     => 'rider-glamorous.htm',
      'title'    => 'Rider',
      'photo'    => 'francesca.jpg',
      'photoalt' => 'Francesca Romana Girodano',
      'submenu'  => array(
          'Glamorous'  => 'rider-glamorous.htm',
          'At Work'    => 'rider-at-work.htm',
          )
     ),
array('link'     => 'people.htm',
      'title'    => 'People',
      'photo'    => 'people.jpg',
      'photoalt' => 'People',
      'submenu'  => array(
          'People'     => 'people.htm',
          'Domiziana'  => 'people-domiziana.htm',
          )
     ),
array('link'     => 'bikes.htm',
      'title'    => 'Bikes',
      'photo'    => 'bike33-3.jpg',
      'photoalt' => 'Bikes',
     ),

array('link'     => 'yamaha-current-condition.htm',
      'title'    => 'Yamaha <br/>YZF 750',
      'photo'    => 'bike19.jpg',
      'photoalt' => 'Yamaha YZF 750',
      'submenu'  => array(
          'Bike and Rider'      => 'yamaha-bike-and-rider.htm',
          'Team Life'           => 'team-yamaha.html',
          'Current Condition'   => 'yamaha-current-condition.htm',
          'Articles'            => 'articles.htm?yamaha',
          )
     ),

array('link'     => 'team-cagiva.htm',
      'title'    => 'Team Life',
      'photo'    => 'paddocks.jpg',
      'photoalt' => 'Team Life',
      'submenu'  => array(
          'Team Life Cagiva' => 'team-cagiva.htm',
          'Team Life Aprila' => 'team-aprilia.htm',
          'Team Life Yamaha' => 'team-yamaha.htm',
          )
     ),

array('link'     => 'ireland-tt.htm',
      'title'    => 'TT &amp; Ireland',
      'photo'    => 'bike71.jpg',
      'photoalt' => 'TT &amp; Ireland',
      'submenu'  => array(
          'Tourist Trophy'     => 'ireland-tt.htm',
          'Ulster Grand Prix'  => 'ireland-ulster-grand-prix.htm',
          'Skerries'           => 'ireland-skerries.htm',
          'Britten'            => 'ireland-britten.htm',
          ),
     ),

array('link'     => 'paddocks.htm',
      'title'    => 'Paddocks',
      'photo'    => 'paddock.jpg',
      'photoalt' => 'Paddocks',
     ),

array('link'     => 'adm-milan-bike-exibition.htm',
      'title'    => 'ADM',
      'photo'    => 'adm.jpg',
      'photoalt' => 'ADM',
      'submenu'  => array(
          'Milan Bike Exibition' => 'adm-milan-bike-exibition.htm',
          'Other Events'         => 'adm-other-events.htm',
          'La Notte Della Moto'  => 'adm-la-notte-della-moto.htm',
          'ADM Articles'         => 'articles.htm?ADM',
          )
     ),
);

$this_page_query = (($_SERVER['QUERY_STRING']) ? "$this_page?$_SERVER[QUERY_STRING]" : $this_page);
if($this_page_query === 'articles.htm')        { $this_page_query = "articles.htm?english"; }
if($this_page       === 'ireland-britten.htm') { $this_page_query = "ireland-britten.htm"; }

echo "<div id='topmenu-spacer'>";
echo "<ul id='dropout'>\n";
foreach($menus as $menu) {
  $liclasses = array();
  if($menu['submenu']){ $liclasses[] = in_array($this_page_query,$menu['submenu']) ? "sububer" : 'submenu'; }
  if($menu['link'] === $this_page_query) { $liclasses[] = 'uberlink'; }
  $liclass = ($liclasses) ? " class='".implode(' ',$liclasses)."'" : '';
  echo "<li$liclass><a href='$menu[link]'> ";
  if($menu['photo'])echo "<img src='$menu_photodir/$menu[photo]' width='100' height='50' border='0' alt='$menu[photoalt]'/><br/> ";
  echo "<span> $menu[title] </span> </a>";
  if($menu['submenu']) {
    echo "\n<ul $ulclass>\n";
    foreach($menu['submenu'] as $menutitle => $menulink) {
      $subclass = ($menulink === $this_page_query) ? " class='uberlink'" : '';
      echo "<li><a href='$menulink'$subclass>$menutitle </a></li>\n";
    }
    echo "</ul>\n";
  }
  echo "</li>\n";
}


//   if(is_array($menu['submenu']) && in_array($this_page_query,$menu['submenu'])) {
//     echo "<li><ul class='minimenu'>";
//     foreach($menu['submenu'] as $menutitle => $menulink) {
//       $uberlink = ($this_page_query == $menulink) ? " class='uberlink'" : '';
//       echo "<li$uberlink><a href='$menulink'>$menutitle</a></li>\n";
//     }
//     echo "</ul><li>";
//   }



echo "<li class='credits'>\n"
   . "<strong>Photos By:</strong><br/>\n"
   . "Nicola Quartullo<br/>\n"
   . "John Watterson<br/>\n"
   . "Oliver</li>\n";

$email   = email_encode('francesca@ukonline.co.uk');
$emailbr = email_encode('francesca') . '<br/>' . email_encode('@ukonline.co.uk');
echo "<li class='credits'>\n"
   . "<strong>Contact:</strong><br/>\n"
   . "<a href='mailto:$email'>email me </a></li>\n";


//echo "<div class='clear'></div>\n";
echo "</ul>\n";
//echo "<div class='clear'></div>\n";
echo "</div>";

// echo "<div id='topmenu' class='dropdown'><table id='dropdown'><tr>";
// foreach($menus as $menu) {
//   echo "<td align='center' valign='top' class='hello'><a href='$menu[link]'>
//   <img src='$menu_photodir/$menu[photo]' width='100' height='50' border='0' alt='$menu[photoalt] '/>
//   $menu[title]";
//   if($menu['submenu']) {
//     echo "\n<ul class='submenu'>\n";
//     echo "<li class='hide'></li><li class='hide'></li>";
//     foreach($menu['submenu'] as $menutitle => $menulink) { echo "<li><a href='$menulink'>$menutitle</li>\n"; }
//     echo "</ul>\n";
//   }
//   echo "</a></td>\n";
// }
// echo "</tr></table></div>\n";


?>


<?php
//  if($sidemenu) echo "<div id='sidemenu'> $sidemenu </div>";
//  if($sidemenu) $mainclass = " class='sidemenu'";

  echo "<div id='main'$mainclass>\n";
  echo "<div class='content article'>\n";

  if($title)    echo "<h2 class='title'>$title</h2>\n";
  if($subtitle) echo "<h3 class='subtitle'>$subtitle</h3>\n";
  $content = preg_replace('/"([^"<>]+)"/','<em>&quot;$1&quot;</em>',$content);
  echo "\n$content\n";

  echo "</div>\n";
  echo "</div>\n";
?>

</div> <!-- end id='body' -->
</body>
</html>
<?php } // END if($dont_print_template == false) ?>
