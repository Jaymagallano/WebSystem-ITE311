<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            margin: 20px;
        }
        
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #e74c3c;
            line-height: 1;
            text-shadow: 3px 3px 0 #e0e0e0;
        }
        
        .error-icon {
            font-size: 80px;
            color: #e74c3c;
            margin: 20px 0;
            animation: shake 0.5s infinite;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .error-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .warning-box i {
            color: #856404;
            margin-right: 8px;
        }
        
        .warning-box p {
            color: #856404;
            font-size: 14px;
        }
        
        .btn-home {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(231, 76, 60, 0.4);
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.5);
        }
        
        .ip-logged {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-icon">
            <i class="fas fa-ban"></i>
        </div>
        <h1 class="error-title">Access Forbidden!</h1>
        <p class="error-message">
            Hindi ka pinapayagang i-access ang page o directory na ito.
            Ang iyong request ay na-block ng security system.
        </p>
        
        <div class="warning-box">
            <p><i class="fas fa-exclamation-triangle"></i> 
            Ang suspicious activity ay naka-log at maaaring i-report sa administrator.</p>
        </div>
        
        <a href="<?php echo base_url(); ?>" class="btn-home">
            <i class="fas fa-home"></i> Bumalik sa Home
        </a>
        
        <p class="ip-logged">
            <i class="fas fa-eye"></i> Your IP: <?php echo $_SERVER['REMOTE_ADDR'] ?? 'Unknown'; ?> has been logged
        </p>
    </div>
</body>
</html>
