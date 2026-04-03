<?php
require_once 'config.php';

// Fetch Virtual Account details to get the VPA (UPI ID)
// This is the correct and most reliable way to "Load" a VA via UPI.
$endpoint = "/v1/accounts/" . ZWITCH_ACCOUNT_ID;
$response_data = call_zwitch($endpoint, 'GET');

$vpa = $response_data['body']['vpa'] ?? null;
$name = $response_data['body']['name'] ?? 'Virtual Account';
$show_qr = false;

if ($vpa && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'] ?? '1.00';
    $remark = "Scale Wallet Load";
    
    // Construct the standard UPI URI
    $upi_uri = "upi://pay?pa=" . $vpa . "&pn=" . urlencode($name) . "&am=" . $amount . "&cu=INR&tn=" . urlencode($remark);
    
    // Create a QR code URL using a reliable public API (QRServer)
    $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($upi_uri);
    
    $show_qr = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPI Intent Flow | Zwitch Pay</title>
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Shared Design System -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .vpa-highlight { color: #38bdf8; font-weight: 600; }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
        <header>
            <div class="logo">⚡ ZWITCH PAY</div>
            <h1>UPI Intent / QR</h1>
            <p class="subtitle">Quickly load funds into your virtual account. Perfect for scan-to-pay checkouts.</p>
        </header>

        <form method="POST">
            <div class="form-group">
                <label for="amount">Deposit Amount</label>
                <div class="input-wrapper">
                    <input type="number" step="0.01" name="amount" id="amount" value="<?php echo htmlspecialchars($amount ?? '100'); ?>" required min="1">
                </div>
            </div>
            <button type="submit" class="btn"><?php echo $show_qr ? 'Update Payment QR' : 'Generate Payment QR'; ?></button>
        </form>

        <?php if ($show_qr): ?>
            <div class="result-card">
                <div class="status-badge">READY TO SCAN</div>
                
                <div class="qr-container">
                    <img src="<?php echo $qr_url; ?>" alt="UPI QR Code">
                </div>

                <div class="payment-info">
                    <div class="info-row">
                        <div class="info-label">Receiver Account</div>
                        <div class="info-value"><?php echo htmlspecialchars($name); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Receiver UPI ID</div>
                        <div class="info-value vpa-highlight"><?php echo $vpa; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Amount to Load</div>
                        <div class="info-value">₹<?php echo number_format($amount, 2); ?></div>
                    </div>
                </div>

                <a href="<?php echo $upi_uri; ?>" class="pay-btn">Open in UPI App</a>
                <p style="font-size: 0.75rem; color: var(--text-dim); margin-top: 1rem;">Scanning on mobile recommended for best experience</p>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $response_data): ?>
            <div class="result-card">
                <div class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2);">ERROR</div>
                <div class="payment-info" style="color: #ef4444; font-size: 0.875rem;">
                    <strong>Failed to initialize Virtual Account connection.</strong><br><br>
                    <?php echo htmlspecialchars(print_r($response_data['body'], true)); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="footer-link">
            <a href="index.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>