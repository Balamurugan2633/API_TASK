<?php

$access_key = '66dcc080-2dd1-11f1-bd4b-479331323d05';
$api_secret = 'fed7e32182199db3910cdbb22d53df6c4fedf3bc';
$account_id = 'va_VjZoGFRRfwp2tG5O4KqM7H2cT'; // Bala tech Virtual Primary Account

$url = "https://api.zwitch.io/v1/accounts/$account_id/payments/upi/collect";
$auth = "Bearer $access_key:$api_secret";

$payload = [
    "amount" => "1.00",
    "currency" => "INR",
    "contact_number" => "9999999999",
    "email_id" => "test@example.com",
    "mtx" => "TXN_" . time(),
    "remitter_vpa_handle" => "anil.reddy@okicici" // This triggers the PUSH notification
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.zwitch.io/icp/upi/intent");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: $auth",
    "Content-Type: application/json",
    "Accept: application/json"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    echo "CURL Error: " . curl_error($ch);
} else {
    echo "HTTP Status Code: $http_code\n";
    echo "Response:\n";
    echo $response;
}
curl_close($ch);
?>
