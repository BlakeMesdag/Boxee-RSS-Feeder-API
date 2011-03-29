<?php
	include('inc/config/config.php');
	include('inc/class/SQLSession.php');
	include('inc/class/PasskeyAuth.php');
        include('inc/class/BoxeeRSSFeedItem.php');
        include('inc/class/BoxeeRSSFeeder.php');

	$auth = new PasskeyAuth();

	if(isset($_GET['passkey']))
	{
		if(!$auth->verifyPasskey($_GET['passkey']))
		{
			return;
		}

	}
	else
	{
		return;
	}

	$_GET['desc'] != "" ? $desc = $_GET['desc'] : $desc = "TV RSS Feed";

        $feed = new BoxeeRSSFeeder($feedTitle, $desc, $feedHomepage, $feedImage);

	$_GET['ext'] != "" ? $ext = $_GET['ext'] : $ext="avi";

        $feed->loadFromDir($baseFolder, $ext);
        $feed->outputRss();
?>
