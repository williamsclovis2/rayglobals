<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache');

$channel_id = 'UCOwcs6zPiOfhBiG2IHWqSVQ';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.youtube.com/channel/' . $channel_id . '/live');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$is_live = false;

if ($response) {
    // Multiple checks to confirm live status
    if (
        strpos($response, '"isLiveBroadcast"') !== false ||
        strpos($response, '"hlsManifestUrl"') !== false ||
        strpos($response, 'watching now') !== false ||
        strpos($response, '"live_playback"') !== false
    ) {
        $is_live = true;
    }
}

echo json_encode([
    'live' => $is_live,
    'http_code' => $http_code
]);
?>