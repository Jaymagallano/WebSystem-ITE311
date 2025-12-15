<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
            line-height: 1;
            text-shadow: 3px 3px 0 #e0e0e0;
        }
        
        .error-icon {
            font-size: 80px;
            color: #ff6b6b;
            margin: 20px 0;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }
        
        .error-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .requested-url {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border-radius: 0 10px 10px 0;
        }
        
        .requested-url label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        
        .requested-url code {
            display: block;
            margin-top: 5px;
            background: #e9ecef;
            padding: 8px 12px;
            border-radius: 5px;
            font-family: monospace;
            color: #e74c3c;
            word-break: break-all;
        }
        
        .btn-home {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }
        
        .suggestions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .suggestions h4 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .suggestions ul {
            list-style: none;
            color: #666;
            font-size: 14px;
        }
        
        .suggestions li {
            margin: 8px 0;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .suggestions li i {
            color: #667eea;
            margin-right: 8px;
        }
        
        .correct-url {
            margin-top: 20px;
            padding: 15px;
            background: #d4edda;
            border: 1px solid #28a745;
            border-radius: 10px;
        }
        
        .correct-url p {
            color: #155724;
            font-size: 14px;
        }
        
        .correct-url a {
            color: #155724;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-icon">
            <i class="fas fa-search"></i>
        </div>
        <h1 class="error-title">Oops! Page Not Found</h1>
        <p class="error-message">
            Ang page na hinahanap mo ay hindi mahanap. 
            Baka mali ang na-type na URL o wala na ang page.
        </p>
        
        <div class="requested-url">
            <label>Hiniling na URL:</label>
            <code><?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/unknown'); ?></code>
        </div>
        
        <div class="correct-url">
            <p><i class="fas fa-lightbulb"></i> Baka ito ang hinahanap mo?</p>
            <p><a href="/ITE311-MAGALLANO/">http://localhost/ITE311-MAGALLANO/</a></p>
        </div>
        
        <a href="/ITE311-MAGALLANO/" class="btn-home">
            <i class="fas fa-home"></i> Pumunta sa Tamang Site
        </a>
        
        <div class="suggestions">
            <h4><i class="fas fa-info-circle"></i> Mga Posibleng Dahilan:</h4>
            <ul>
                <li><i class="fas fa-keyboard"></i> Typo sa URL (hal. MAGA instead of MAGALLANO)</li>
                <li><i class="fas fa-folder-minus"></i> Ang folder ay inalis o pinalitan ng pangalan</li>
                <li><i class="fas fa-link"></i> Expired o broken link</li>
            </ul>
        </div>
    </div>
</body>
</html>
