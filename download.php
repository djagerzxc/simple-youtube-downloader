<?php
session_start();
require_once 'src/YoutubeDownloader.php';
use Src\YoutubeDownloader AS Ydl;
$video = $_SESSION['video'];
if ($_GET) {
	$v = $_GET['v'];
	if ($_GET['opt'] == 'and') {
		$vid = ['title' => $video['title'],'type' => explode('/',$video['video&audio'][$v]['type'])[1],'size' => $video['video&audio'][$v]['size_bit'],'url' => $video['video&audio'][$v]['url']];
	} else {
		$vid = ['title' => $video['title'],'type' => explode('/',$video['video|audio'][$v]['type'])[1],'size' => $video['video|audio'][$v]['size_bit'],'url' => $video['video|audio'][$v]['url']];
	}
	if ($vid) {
	$url = urldecode($vid['url']);
  $size = $vid['size'];
  $title = $vid['title'];
  $type = $vid['type'];
  $fileName = $title . '.' . $type;
      
  header("Cache-Control: public");
  header("Content-Description: File Transfer");
  header("Content-Disposition: attachment;filename=\"$fileName\"");
  header('Content-Type: application/octet-stream');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  if ($size)
  {
  	header('Content-Length: ' . $size);
  }
  ob_clean();
  flush();
  readfile($url);
  }
}
#print_r($video) and die;
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<title>Download</title>
		<link href="css/download.css" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
	</head>
	<body>
		<main>
			<div class="image">
				<img src="<?= $video['thumbnail'] ?>">
			</div>
			<p class="title"><?= $video['title'] ?></p>
			<p class="option">Video Dan Audio >></p>
			<table border="1">
				<thead>
					<tr>
						<th>Resolusi</th>
						<th>Tipe</th>
						<th>Ukuran</th>
						<th>Tombol</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($video['video&audio'] as $i => $item) : ?>
					<tr>
						<td><?= $item['quality'] ?></td>
						<td><?= $item['type'] ?></td>
						<td><?= $item['size_convert'] ?></td>
						<td><a href="?v=<?= $i ?>&opt=and"><i class="fas fa-download"></i></a></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
			<p class="option">Video Atau Audio >></p>
			<table border="1">
				<thead>
					<tr>
						<th>Resolusi</th>
						<th>Tipe</th>
						<th>Ukuran</th>
						<th>Tombol</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($video['video|audio'] as $i => $item) : ?>
					<tr>
						<td><?= $item['quality'] ?></td>
						<td><?= $item['type'] ?></td>
						<td><?= $item['size_convert'] ?></td>
						<td><a href="?v=<?= $i ?>&opt=or"><i class="fas fa-download"></i></a></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</main>
	</body>
</html>
