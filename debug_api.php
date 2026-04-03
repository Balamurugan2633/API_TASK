<?php
require_once 'config.php';

ob_start();
echo "<h1>Zwitch Authentication Debugger</h1>";
echo "<p>Testing different authentication patterns for your keys.</p>";

$tests = [
    "Production (Bearer)" => "https://api.zwitch.io/v1/accounts",
    "Production (Basic)" => "https://api.zwitch.io/v1/accounts",
    "Sandbox (Bearer)" => "https://sandbox.zwitch.io/v1/accounts",
    "Sandbox (Basic)" => "https://sandbox.zwitch.io/v1/accounts",
];

foreach ($tests as $label => $url) {
    echo "<h3>Testing: $label</h3>";
    echo "URL: $url<br>";

    $is_basic = strpos($label, 'Basic') !== false;
    $key = ZWITCH_ACCESS_KEY;
    $secret = ZWITCH_API_SECRET;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);

    if ($is_basic) {
        $auth = "Basic " . base64_encode("$key:$secret");
    } else {
        $auth = "Bearer $key:$secret";
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $auth",
        "Accept: application/json"
    ]);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $header_content = substr($response, 0, $header_size);
    $body_content = substr($response, $header_size);

    // Extract Trace ID
    $trace_id = 'N/A';
    if (preg_match('/x-zwitch-trace-id:\s*(.*)/i', $header_content, $matches)) {
        $trace_id = trim($matches[1]);
    }

    echo "<strong>HTTP Status: $http_code</strong><br>";
    echo "Trace ID: <code style='background:#eee;padding:2px;'>$trace_id</code><br>";
    echo "Response: <pre>" . htmlspecialchars($body_content) . "</pre>";
    echo "<hr>";
}
$output = ob_get_clean();
file_put_contents('debug_results.html', $output);
echo "Results saved to debug_results.html";
?>