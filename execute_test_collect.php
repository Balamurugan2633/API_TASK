<?php
require_once 'config.php';

// Test Data
$test_vpa = "success@upi"; // Common test VPA for sandbox
$amount = "1.00";
$mtx = "TEST_COLLECT_" . time();

// Endpoint
$endpoint = "/v1/accounts/" . ZWITCH_ACCOUNT_ID . "/payments/upi/collect";

$payload = [
    "amount" => $amount,
    "currency" => "INR",
    "contact_number" => "9106737288",
    "email_id" => "test@example.com",
    "mtx" => $mtx,
    "payer_vpa" => $test_vpa,
    "remark" => "Task Documentation Test"
];

echo "--- INITIATING TEST COLLECT REQUEST ---\n";
echo "URL: " . ZWITCH_BASE_URL . $endpoint . "\n";
echo "VPA: $test_vpa\n";
echo "Amount: $amount\n\n";

$response = call_zwitch($endpoint, 'POST', $payload);

echo "--- API RESPONSE ---\n";
echo "HTTP Status: " . $response['code'] . "\n";
echo "Trace ID: " . $response['trace_id'] . "\n";
echo "Body:\n";
echo json_encode($response['body'], JSON_PRETTY_PRINT);
echo "\n---------------------------------------\n";
?>
