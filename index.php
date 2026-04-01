<?php

$access_key = '66dcc080-2dd1-11f1-bd4b-479331323d05';
$api_secret = 'fed7e32182199db3910cdbb22d53df6c4fedf3bc';
$api_key_uuid = '66dcc080-2dd1-11f1-bd4b-479331323d05';
$account_id = 'va_VjZoGFRRfwp2tG5O4KqM7H2cT';

$auth_header = "Authorization: Bearer $access_key:$api_secret";

function make_request($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
        'Content-Type: application/json',
        'Accept: application/json'
    ], $headers));
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $http_code, 'body' => json_decode($response, true) ?: $response];
}

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Zwitch API Integration Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; }
        .container { background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 600px; text-align: center; }
        h1 { color: #333; margin-bottom: 2rem; font-weight: 300; }
        .btn { display: inline-block; padding: 12px 25px; margin: 10px; border-radius: 50px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-blue { background: #007bff; color: white; }
        .btn-green { background: #28a745; color: white; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card { background: #fff9db; border: 1px solid #ffe066; padding: 15px; border-radius: 8px; margin-top: 2rem; font-size: 0.9rem; text-align: left; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Zwitch Integration Dashboard</h1>
        <p>Choose an action to test the UPI Payment integration:</p>
        
        <a href='generate_upi_link.php' class='btn btn-blue'>Generate UPI Payment Link</a>
        <a href='create_collect_request.php' class='btn btn-green'>Create UPI Collect Request (Push)</a>

        <div class='card'>
            <strong>Current Credentials Hooked:</strong><br>
            Access Key (API Key): $access_key <br>
            Virtual Account ID: $account_id
        </div>
    </div>
</body>
</html>";
?>
