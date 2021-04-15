<?php
session_start();
require_once 'src/YoutubeDownloader.php';
use Src\YoutubeDownloader AS Ydl;
if ($_POST) {
	$ydl = new Ydl();
	if (isset($_POST['url'])) {
		$video = $ydl->video_id($_POST['url']);
		if ($video == 'INVALID URL') {
			echo '<i style="color:red;padding-left:10px">TAUTAN TIDAK VALID</i>';
		} else if ($video == 'COPYRIGHT') {
			echo '<i style="color:red;padding-left:10px">VIDEO MEMILIKI HAK CIPTA</i>';
		} else {
			$_SESSION['video'] = $video;
			header('Location: download.php');
		}
	}
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Youtube Downloader</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  </head>
  <body>
  	<main>
  		<p>Maaf ya kalo desain tidak bagus. Ini hanya contoh saja</p>
  		<br>
    	<p>Contoh tautan :</p>
    	<br>
    	<p>- https://www.youtube.com/watch/v=ViOcTCmo62s</p>
    	<p>- https://youtu.be/ViOcTCmo62s</p>
    	<p>- ViOcTCmo62s</p>
    	<br>
    	<div class="form">
    		<form method="post">
    				<input type="text" name="url" placeholder="Masukan tautan video...">
    				<button type="submit">Unduh</button>
    		</form>
    	</div>
    </main>
  </body>
</html>
