<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// User's Credentials from Image
$access_key = 'ak_test_cM7M7FHfXQHBa8l8Ry20xayfnyUc07zta4N';
$api_secret = '948005f9fdc951237b752b18912e6a2eef875bf2';
$api_key_uuid = 'ab794780-03ef-11f1-ba5d-cb6d34960307';

$url = "https://api.zwitch.io/v1/accounts";

$auth_methods = [
    "Bearer AccessKey:Secret" => "Bearer $access_key:$api_secret",
    "Bearer UUID:Secret" => "Bearer $api_key_uuid:$api_secret",
    "Basic AccessKey:Secret" => "Basic " . base64_encode("$access_key:$api_secret"),
    "Basic UUID:Secret" => "Basic " . base64_encode("$api_key_uuid:$api_secret"),
];

foreach ($auth_methods as $label => $header) {
    echo "--- Testing $label ---\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $header",
        "Content-Type: application/json",
        "Accept: application/json"
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Status: $http_code\n";
    echo "Response: " . substr($response, 0, 200) . "...\n\n";
}
?>
