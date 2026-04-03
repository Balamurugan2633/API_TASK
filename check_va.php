<?php
require_once 'config.php';

echo "<h1>Fetching VA Details</h1>";
$endpoint = "/v1/accounts/" . ZWITCH_ACCOUNT_ID;
$response = call_zwitch($endpoint, 'GET');

echo "<h3>Status: " . $response['code'] . "</h3>";
echo "<pre>" . print_r($response['body'], true) . "</pre>";
?>