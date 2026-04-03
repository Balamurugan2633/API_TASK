<?php
require_once 'config.php';

$response_data = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? '1.00';
    $vpa = $_POST['vpa'] ?? '';
    $mtx = "COLLECT_" . time();

    // The correct endpoint for a Push Collect request
    $endpoint = "/v1/accounts/" . ZWITCH_ACCOUNT_ID . "/payments/upi/collect";

    $payload = [
        "amount" => $amount,
        "currency" => "INR",
        "contact_number" => "9106737288", // Use a valid number for sandbox
        "email_id" => "test@example.com",
        "mtx" => $mtx,
        "payer_vpa" => $vpa,
        "remark" => "Direct Payment for Order #$mtx"
    ];

    $response_data = call_zwitch($endpoint, 'POST', $payload);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPI Collect | Zwitch Pay</title>
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Shared Design System -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
        <header>
            <div class="logo">⚡ ZWITCH PAY</div>
            <h1>UPI Collect</h1>
            <p class="subtitle">Send a push notification directly to the customer's UPI app.</p>
        </header>

        <form method="POST">
            <div class="form-group">
                <label for="vpa">Customer UPI ID (VPA)</label>
                <div class="input-wrapper">
                    <input type="text" name="vpa" id="vpa" placeholder="customer@upi" value="<?php echo htmlspecialchars($_POST['vpa'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="amount">Request Amount</label>
                <div class="input-wrapper">
                    <input type="number" step="0.01" name="amount" id="amount" value="<?php echo htmlspecialchars($_POST['amount'] ?? '1.00'); ?>" required min="1">
                </div>
            </div>

            <button type="submit" class="btn">Send Payment Request</button>
        </form>

        <?php if ($response_data): ?>
            <div class="result-card">
                <?php if ($response_data['code'] == 200 || $response_data['code'] == 201): ?>
                    <div class="status-badge">REQUEST SENT</div>
                    <h3 style="color: #10b981; margin-bottom: 0.5rem;">✅ Success!</h3>
                    <p style="color: var(--text-dim); font-size: 0.875rem;">Your collect request for ₹<?php echo $amount; ?> has been sent to <strong><?php echo htmlspecialchars($vpa); ?></strong>.</p>
                    
                    <div class="payment-info" style="margin-top: 1.5rem;">
                        <div class="info-row">
                            <div class="info-label">Transaction ID</div>
                            <div class="info-value"><?php echo $response_data['body']['data']['id'] ?? $mtx; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Trace ID</div>
                            <div class="info-value" style="font-size: 0.75rem; color: #94a3b8;"><?php echo $response_data['trace_id']; ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2);">ERROR</div>
                    <div class="payment-info" style="color: #ef4444; font-size: 0.875rem;">
                        <strong>Status: <?php echo $response_data['code']; ?></strong><br><br>
                        <?php echo htmlspecialchars(print_r($response_data['body'], true)); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="footer-link">
            <a href="index.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>