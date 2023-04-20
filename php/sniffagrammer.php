<?php

// type can be 'tag' or 'user'
$type = 'user';

// if type is 'tag', the script will search for #orsifrancesco
// if type is 'user', the script will search for @orsifrancesco
$value = 'orsifrancesco';

// --------------------------------------------------

header('Content-Type: application/json; charset=utf-8');
$value = strtolower($value);
$context = stream_context_create(array('http' => array('ignore_errors' => true)));
$inputUrl = file_get_contents('https://orsi.me/sniffagram/?' . (($type !== 'tag' && $type !== 'user') ? 'tag' : $type) . '=' . $value, false, $context);

$input = $inputUrl ? json_decode($inputUrl, TRUE) : array();
$inputData = $input && $input['data'] ? $input : array('data' => array());
$imagesFolder = 'images/' . $type . '/' . $value;

if (!file_exists('temp')) { mkdir('temp', 0777, true); }
if (!file_exists('temp/' . $type)) { mkdir('temp/' . $type, 0777, true); }
if (!file_exists('temp/' . $type . '/' . $value)) { mkdir('temp/' . $type . '/' . $value, 0777, true); }
if (!file_exists('images')) { mkdir('images', 0777, true); }
if (!file_exists('images/' . $type)) { mkdir('images/' . $type, 0777, true); }
if (!file_exists($imagesFolder)) { mkdir($imagesFolder, 0777, true); }

$output = array(
	$type => $value,
	'http_response_header' => $http_response_header,
	'timestamp' => time()
);
if($inputData['remainingDailyRequests']) $output['remainingDailyRequests'] = $inputData['remainingDailyRequests'];
for($i = 0; $i < count($inputData['data']); $i++) {
	$url = $inputData['data'][$i]['imageUrl'];
	$checksum = "temp/" . $type . '/' . $value . '/' . md5($url);
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