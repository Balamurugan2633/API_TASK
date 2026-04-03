<?php
/**
 * Zwitch API Configuration
 * 
 * Centralized settings for Zwitch UPI Integration.
 */

// --- API CREDENTIALS ---

// Standard API Keys (Recommended)
define('ZWITCH_ACCESS_KEY', 'ak_test_cM7M7FHfXQhABa8l8Ry20xayfnyUcO7zta4N');
define('ZWITCH_API_SECRET', 'sk_test_mQOcjTNZBi1yzkUsrp4BMoztIzcZSUCBzXZH');

// VIRTUAL ACCOUNT ID
define('ZWITCH_ACCOUNT_ID', 'va_0hCN64ybhLlUP4gYzduxZ2rwb');

// --- ENVIRONMENT ---
// Point to the official api.zwitch.io endpoint. 
// Your ak_test/sk_test keys will automatically put you in Sandbox mode.
define('ZWITCH_BASE_URL', 'https://api.zwitch.io');

/**
 * Shared Helper Function for Zwitch API Requests
 */
function call_zwitch($endpoint, $method = 'GET', $data = null)
{
    // Official Zwitch Auth Format: Bearer AccessKey:SecretKey
    $auth = "Bearer " . ZWITCH_ACCESS_KEY . ":" . ZWITCH_API_SECRET;
    //echo $auth . "<br>" . $endpoint . "<br>" . $method . "<br>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, ZWITCH_BASE_URL . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HEADER, true); // We want headers to find the Trace ID

    $headers = [
        "Authorization: $auth",
        "Content-Type: application/json",
        "Accept: application/json"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //print_r($data);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    // LOCAL DEVELOPMENT FIX: Disable SSL verification for XAMPP users
    // This resolves the "HTTP Status: 0" issue caused by missing certificates
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    // DEBUG: Automatically show debug info if a connection error happens (Status 0)
    // Or if the user manually adds ?debug=1 to the URL
    if ($http_code == 0 || isset($_GET['debug'])) {
        echo "<div style='background:#fefce8; padding:1rem; border:2px solid #fde047; color:#854d0e; margin-bottom:1rem; border-radius:1rem; font-family:sans-serif;'>";
        echo "<strong>🛠️ Connection Debugger:</strong><br>";
        if ($error) {
            echo "<span style='color:#ef4444;'><strong>CURL ERROR:</strong> $error</span><br>";
            echo "<small>Tip: This is usually caused by network issues or an invalid API URL.</small><br>";
        }
        echo "<details style='margin-top:0.5rem;'><summary>View Raw Response Data</summary>";
        echo "<pre style='background:#000; color:#0f0; padding:1rem; overflow:auto;'>";
        echo htmlspecialchars($response);
        echo "</pre></details>";
        echo "</div>";
    }

    // Split headers and body
    $header_content = substr($response, 0, $header_size);
    $body_content = substr($response, $header_size);

    // Find Trace ID for support
    $trace_id = 'Unknown';
    if (preg_match('/x-zwitch-trace-id:\s*(.*)/i', $header_content, $matches)) {
        $trace_id = trim($matches[1]);
    }

    $body = json_decode($body_content, true);

    return [
        'code' => $http_code,
        'body' => $body ?: $body_content,
        'trace_id' => $trace_id,
        'error' => $error
    ];
}
?>