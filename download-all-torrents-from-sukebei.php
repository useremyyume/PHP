<?php
//Developed by EmyYume
set_time_limit(0);
$code = strtoupper(trim(stripslashes(htmlspecialchars($_GET['code']))));
$limit = trim(stripslashes(htmlspecialchars($_GET['limit'])));
if (!$code) die();
$downloaded = 0;
$page = 1;
$url = "https://sukebei.nyaa.si/user/offkab?f=0&c=0_0&q=%2B%2B%2B+%5BHD%5D+$code&p=$page";
while (true) {
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_URL => $url
	));
	$result = curl_exec($ch);
	curl_close($ch);
	preg_match_all("/\w*\.torrent/", $result, $matches);
	if (!$matches[0]) {
		echo "Complete.";
		return;
	}
	foreach ($matches[0] as $id) {
		$url = "https://sukebei.nyaa.si/download/$id";
		$file = fopen("download/$id", "w") or die("Please create a folder \"download\" at this directory.");
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_URL => $url,
			CURLOPT_FILE => $file
		));
		curl_exec($ch);
		curl_close($ch);
		fclose($file);
		++$downloaded;
		if ($downloaded == $limit) {
			echo "Complete.";
			return;
		}
	}
	$url = "https://sukebei.nyaa.si/user/offkab?f=0&c=0_0&q=%2B%2B%2B+%5BHD%5D+$code&p=" . ++$page;
}