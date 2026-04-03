<?php
require_once 'config.php';

echo "<h1>Listing All Virtual Accounts</h1>";
$endpoint = "/v1/accounts";
$response = call_zwitch($endpoint, 'GET');

echo "<h3>Status: " . $response['code'] . "</h3>";
echo "<pre>" . print_r($response['body'], true) . "</pre>";

if ($response['code'] == 200 && isset($response['body']['data']) && !empty($response['body']['data'])) {
    echo "<h2>Found Accounts:</h2><ul>";
    foreach ($response['body']['data'] as $acc) {
        echo "<li>ID: <strong>" . $acc['id'] . "</strong> (" . ($acc['name'] ?? 'Unnamed') . ")</li>";
    }
    echo "</ul><p>Copy one of these IDs and update ZWITCH_ACCOUNT_ID in config.php</p>";
} else {
    echo "<p style='color:red;'>No accounts found or error fetching accounts. You may need to create a Virtual Account first in the dashboard.</p>";
}
?>
