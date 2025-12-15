<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛡️ Security Alert - Access Blocked</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        /* Animated background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.1;
        }
        
        .bg-animation span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: #e74c3c;
            animation: move 25s infinite;
            bottom: -150px;
        }
        
        .bg-animation span:nth-child(1) { left: 25%; animation-delay: 0s; }
        .bg-animation span:nth-child(2) { left: 10%; animation-delay: 2s; width: 10px; height: 10px; }
        .bg-animation span:nth-child(3) { left: 70%; animation-delay: 4s; }
        .bg-animation span:nth-child(4) { left: 40%; animation-delay: 0s; width: 30px; height: 30px; }
        .bg-animation span:nth-child(5) { left: 65%; animation-delay: 0s; }
        .bg-animation span:nth-child(6) { left: 75%; animation-delay: 3s; }
        .bg-animation span:nth-child(7) { left: 35%; animation-delay: 7s; }
        .bg-animation span:nth-child(8) { left: 50%; animation-delay: 15s; width: 15px; height: 15px; }
        .bg-animation span:nth-child(9) { left: 20%; animation-delay: 2s; width: 25px; height: 25px; }
        .bg-animation span:nth-child(10) { left: 85%; animation-delay: 0s; width: 10px; height: 10px; }
        
        @keyframes move {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
        
        .container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
            max-width: 550px;
            margin: 20px;
            position: relative;
            z-index: 1;
            border: 3px solid #e74c3c;
        }
        
        .shield-icon {
            font-size: 100px;
            color: #e74c3c;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .alert-title {
            font-size: 32px;
            color: #e74c3c;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .alert-subtitle {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        
        .danger-box {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        
        .danger-box h3 {
            margin-bottom: 10px;
        }
        
        .danger-box p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .attack-info {
            background: #f8f9fa;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border-radius: 0 10px 10px 0;
        }
        
        .attack-info h4 {
            color: #e74c3c;
            margin-bottom: 10px;
        }
        
        .attack-info code {
            background: #e9ecef;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
            color: #c0392b;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 20px 0;
            text-align: left;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
        }
        
        .info-item label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        
        .info-item span {
            display: block;
            font-size: 13px;
            color: #333;
            font-weight: 600;
            word-break: break-all;
        }
        
        .btn-home {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
        }
        
        .warning-footer {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
        }
        
        .warning-footer i {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
    </div>
    
    <div class="container">
        <div class="shield-icon">
            <i class="fas fa-shield-halved"></i>
        </div>
        
        <h1 class="alert-title">🚫 ACCESS BLOCKED!</h1>
        <p class="alert-subtitle">Cyber Fortress Security System</p>
        
        <div class="danger-box">
            <h3><i class="fas fa-exclamation-triangle"></i> Suspicious Activity Detected</h3>
            <p>Ang iyong request ay na-identify bilang potential security threat at na-block ng aming security system.</p>
        </div>
        
        <div class="attack-info">
            <h4><i class="fas fa-bug"></i> Detected Attack Pattern:</h4>
            <p>Possible <code>Directory Traversal</code> or <code>Injection Attack</code></p>
            <p style="margin-top: 10px; font-size: 13px; color: #666;">
                Ang paggamit ng <code>../</code>, <code>SQL keywords</code>, o <code>script injection</code> ay strictly prohibited.
            </p>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <label>Your IP Address</label>
                <span><?php echo $_SERVER['REMOTE_ADDR'] ?? 'Unknown'; ?></span>
            </div>
            <div class="info-item">
                <label>Timestamp</label>
                <span><?php echo date('Y-m-d H:i:s'); ?></span>
            </div>
            <div class="info-item">
                <label>Request Method</label>
                <span><?php echo $_SERVER['REQUEST_METHOD'] ?? 'Unknown'; ?></span>
            </div>
            <div class="info-item">
                <label>User Agent</label>
                <span><?php echo substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 30) . '...'; ?></span>
            </div>
        </div>
        
        <a href="/ITE311-MAGALLANO/" class="btn-home">
            <i class="fas fa-home"></i> Bumalik sa Safe Zone
        </a>
        
        <div class="warning-footer">
            <p><i class="fas fa-video"></i> Lahat ng suspicious activities ay naka-log at maaaring i-report.</p>
            <p style="margin-top: 5px;">Kung ito ay isang pagkakamali, i-contact ang administrator.</p>
        </div>
    </div>
    
    <?php
    // Log the attack attempt
    $log_file = 'application/logs/security_attacks.log';
    $log_data = date('Y-m-d H:i:s') . ' | ';
    $log_data .= 'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . ' | ';
    $log_data .= 'URI: ' . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . ' | ';
    $log_data .= 'Query: ' . ($_SERVER['QUERY_STRING'] ?? 'None') . ' | ';
    $log_data .= 'User-Agent: ' . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "\n";
    
    @file_put_contents($log_file, $log_data, FILE_APPEND);
    ?>
</body>
</html>
