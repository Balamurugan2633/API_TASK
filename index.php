<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fintech Dashboard | Zwitch Pay</title>
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Shared Design System -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .container {
            max-width: 900px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            padding: 2rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.03), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .dashboard-card:hover::before {
            transform: translateX(100%);
        }

        .dashboard-card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--accent-primary);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.4);
        }

        .card-icon {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-main);
        }

        .card-description {
            font-size: 0.9375rem;
            color: var(--text-dim);
            line-height: 1.6;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        .card-action {
            font-weight: 600;
            color: var(--accent-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: gap 0.3s;
        }

        .dashboard-card:hover .card-action {
            gap: 0.75rem;
        }

        .badge {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-intent { background: rgba(139, 92, 246, 0.1); color: var(--accent-primary); }
        .badge-collect { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }

        .footer {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid var(--glass-border);
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-dim);
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(16, 185, 129, 0.05);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(16, 185, 129, 0.1);
        }

        .pulse {
            width: 8px;
            height: 8px;
            background: var(--success-color);
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
        <header>
            <div class="logo">⚡ ZWITCH PAY</div>
            <h1>Payment Dashboard</h1>
            <p class="subtitle">Welcome to your premium UPI integration suite. Select a flow to get started.</p>
        </header>

        <div class="grid">
            <!-- UPI INTENT FLOW -->
            <a href="generate_upi_link.php" class="dashboard-card">
                <span class="badge badge-intent">Recommended</span>
                <div class="card-icon">🔗</div>
                <div class="card-title">UPI Intent / QR</div>
                <div class="card-description">
                    Generate scannable QR codes and deep links for mobile apps. Best for direct bank transfers and merchant payments.
                </div>
                <div class="card-action">Launch Intent Flow →</div>
            </a>

            <!-- UPI COLLECT FLOW -->
            <a href="create_collect_request.php" class="dashboard-card">
                <span class="badge badge-collect">Direct Push</span>
                <div class="card-icon">📲</div>
                <div class="card-title">UPI Collect</div>
                <div class="card-description">
                    Send a push notification directly to the customer's UPI app. They just need to enter their PIN to approve.
                </div>
                <div class="card-action">Launch Collect Flow →</div>
            </a>
        </div>

        <div class="footer">
            <div class="status-indicator">
                <div class="pulse"></div>
                <span style="color: var(--success-color); font-weight: 600;">System Online</span>
            </div>
            <p>Connected to Virtual Account: <strong><?php echo ZWITCH_ACCOUNT_ID; ?></strong></p>
            <p style="margin-top: 0.5rem; opacity: 0.6;">Running on Zwitch Sandbox Environment</p>
        </div>
    </div>
</body>
</html>