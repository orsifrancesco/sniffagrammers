<?php

$username = 'orsifrancesco'; // <-- add your username here.. that's it!!

header('Content-Type: application/json; charset=utf-8');
$username = strtolower($username);
$context = stream_context_create(array('http' => array('ignore_errors' => true)));
$inputUrl = file_get_contents('https://orsi.me/sniffagram/?user=' . $username, false, $context);

$input = $inputUrl ? json_decode($inputUrl, TRUE) : array();
$inputData = $input && $input['data'] ? $input : array('data' => array());
$imagesFolder = 'images/' . $username;

if (!file_exists('temp')) { mkdir('temp', 0777, true); }
if (!file_exists('temp/' . $username)) { mkdir('temp/' . $username, 0777, true); }
if (!file_exists('images')) { mkdir('images', 0777, true); }
if (!file_exists($imagesFolder)) { mkdir('images/' . $username, 0777, true); }

$output = array(
	'username' => $username,
	'http_response_header' => $http_response_header,
	'timestamp' => time()
);
if($inputData['remainingDailyRequests']) $output['remainingDailyRequests'] = $inputData['remainingDailyRequests'];
for($i = 0; $i < count($inputData['data']); $i++) {
	$url = $inputData['data'][$i]['imageUrl'];
	$checksum = "temp/" . $username . '/' . md5($url);
	$checksumExist = file_exists($checksum);
	if(!$checksumExist) {
		$fileName = strtok(basename($url), '?');
		$file = file_get_contents($url);
		file_put_contents($imagesFolder . '/' . time() . '_' . $fileName, $file);
		file_put_contents($checksum, '');
		if(!isset($output['newImagesDownloaded'])) $output['newImagesDownloaded'] = array();
		$output['newImagesDownloaded'][] = array(
			'checksum' => $checksum,
			'url' => $url,
			'fileName' => $fileName
		);
	}
}

$output['totalImagesDownloaded'] = count(glob($imagesFolder . "/*"));

$json = json_encode($output, JSON_PRETTY_PRINT);
echo $json;

?>