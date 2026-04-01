<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$access_key = 'ak_test_cM7M7FHfXQhABa8l8Ry20xayfnyUcO7zta4N';
$api_secret = '948005f9fdc951237b752b18912e6a2eef875bf2';

$auth = "Bearer $access_key:$api_secret";

function call_zwitch($url, $method = 'GET', $data = null) {
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
    return ['code' => $info['http_code'], 'body' => $res];
}

echo "--- LIST ACCOUNTS ---\n";
$accounts = call_zwitch("https://api.zwitch.io/v1/accounts");
echo "Code: " . $accounts['code'] . "\n";
echo "Body: " . $accounts['body'] . "\n";

$data = json_decode($accounts['body'], true);
if ($accounts['code'] == 200 && !empty($data['data'])) {
    $aid = $data['data'][0]['id'];
    echo "\n--- CREATE UPI COLLECT (Account: $aid) ---\n";
    $collect = call_zwitch("https://api.zwitch.io/v1/accounts/$aid/payments/upi/collect", 'POST', [
        'remitter_vpa_handle' => 'anil.reddy@okicici', // Assuming a test VPA or a real one is needed
        'amount' => 10,
        'expiry_in_minutes' => 10,
        'remark' => 'Test Payment',
        'merchant_reference_id' => 'TXN_' . time()
    ]);
    echo "Code: " . $collect['code'] . "\n";
    echo "Body: " . $collect['body'] . "\n";
} else {
    echo "\nFailed to fetch account ID. Check keys or permissions.\n";
}
?>
