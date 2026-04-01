<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Credentials from PG API Keys
$access_key = '66dcc080-2dd1-11f1-bd4b-479331323d05';
$api_secret = 'fed7e32182199db3910cdbb22d53df6c4fedf3bc';
$account_id = 'va_VjZoGFRRfwp2tG5O4KqM7H2cT'; 

$auth = "Bearer $access_key:$api_secret";

function call_zwitch($url, $method = 'POST', $data = null) {
    global $auth;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $auth",
        "Content-Type: application/json",
        "Accept: application/json"
    ]);
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return ['code' => $info['http_code'], 'body' => json_decode($res, true)];
}

echo "<h1>Zwitch Payment Link Generator</h1>";

// Create UPI Intent Link (Directly using ICP Endpoint)
echo "<h3>Generating UPI Intent Link...</h3>";
$intent_data = [
    "amount" => "1.00", 
    "currency" => "INR",
    "contact_number" => "9999999999",
    "email_id" => "test@example.com",
    "mtx" => "TXN_" . time()
];

$intent_res = call_zwitch("https://api.zwitch.io/icp/upi/intent", 'POST', $intent_data);

if ($intent_res['code'] == 200 || $intent_res['code'] == 201) {
    $link = $intent_res['body']['link'] ?? $intent_res['body']['intent_string'] ?? null;
    if ($link) {
        echo "<div style='padding: 20px; background: #e7f3ff; border: 1px solid #b3d7ff; border-radius: 5px;'>";
        echo "<strong>Test Payment Link Generated!</strong><br><br>";
        echo "<a href='$link' target='_blank' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Open in UPI App</a><br><br>";
        echo "Copy Link: <input type='text' value='$link' readonly style='width: 100%; padding: 5px;'>";
        echo "</div>";
    } else {
        echo "Error: Link not found in response. <pre>" . print_r($intent_res['body'], true) . "</pre>";
    }
} else {
    echo "Error creating UPI intent: " . $intent_res['code'] . "<br>";
    echo "<pre>" . print_r($intent_res['body'], true) . "</pre>";
    
    if ($intent_res['code'] == 400 && strpos(json_encode($intent_res['body']), 'merchant details') !== false) {
        echo "<p style='color: red;'><strong>Troubleshooting Tip:</strong> This error (400) usually means your Zwitch KYC is still pending or not fully activated for Payment Gateway features. Please check your Dashboard.</p>";
    }
}
?>
