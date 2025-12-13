<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Learning Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            max-width: 440px;
            width: 100%;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            border: none;
        }

        .register-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 28px 25px;
            text-align: center;
            border-bottom: 3px solid #3a6cb8;
        }

        .register-header i {
            font-size: 45px;
            margin-bottom: 12px;
            opacity: 0.95;
        }

        .register-header h2 {
            margin: 0;
            font-size: 26px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .register-header p {
            margin: 8px 0 0 0;
            opacity: 0.85;
            font-size: 14px;
            font-weight: 300;
        }

        .register-body {
            padding: 28px 30px;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 15px;
            letter-spacing: 0.3px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 16px;
        }

        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #2a5298 !important;
            z-index: 10;
            font-size: 15px;
        }

        .form-control {
            border: 2px solid #d0d7de;
            padding: 11px 15px 11px 45px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 15px;
            background-color: #fafbfc;
        }

        .form-control:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.1);
            outline: none;
            background-color: white;
        }

        .btn-register {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 16px;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(30, 60, 114, 0.3);
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #fee;
            color: #c33;
        }

        .alert-success {
            background-color: #efe;
            color: #3c3;
        }

        .text-danger {
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .login-link {
            text-align: center;
            margin-top: 18px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #2a5298;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: #1e3c72;
            text-decoration: underline;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #2a5298 !important;
            z-index: 10;
            font-size: 16px;
        }

        .password-toggle:hover {
            color: #1e3c72 !important;
        }

        .password-strength {
            height: 5px;
            border-radius: 5px;
            margin-top: 8px;
            background: #e0e0e0;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
        }

        .strength-weak { background: #f44336; width: 33%; }
        .strength-medium { background: #ff9800; width: 66%; }
        .strength-strong { background: #4caf50; width: 100%; }

        @media (max-width: 576px) {
            .register-header {
                padding: 30px 20px;
            }

            .register-body {
                padding: 30px 25px;
            }

            .register-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="card register-card">
            <div class="register-header">
                <i class="bi bi-person-plus-fill"></i>
                <h2>Create Account</h2>
                <p>Start your learning journey today</p>
            </div>
            <div class="register-body">
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <?= form_open('register', ['id' => 'registerForm']) ?>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <div class="input-group-custom">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" class="form-control" name="name" 
                                   placeholder="Enter your full name" 
                                   value="<?= set_value('name') ?>" 
                                   required>
                        </div>
                        <?= form_error('name', '<small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> ', '</small>') ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="input-group-custom">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" class="form-control" name="email" 
                                   placeholder="Enter your email" 
                                   value="<?= set_value('email') ?>" 
                                   required>
                        </div>
                        <?= form_error('email', '<small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> ', '</small>') ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <i class="bi bi-shield-lock-fill"></i>
                            <input type="password" class="form-control" name="password" 
                                   id="password" 
                                   placeholder="Create a strong password" 
                                   required>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                        <?= form_error('password', '<small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> ', '</small>') ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group-custom">
                            <i class="bi bi-shield-lock-fill"></i>
                            <input type="password" class="form-control" name="password_confirm" 
                                   id="password_confirm" 
                                   placeholder="Confirm your password" 
                                   required>
                        </div>
                        <?= form_error('password_confirm', '<small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> ', '</small>') ?>
                    </div>
                    
                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-person-plus-fill"></i> Create Account
                    </button>
                    
                    <div class="login-link">
                        Already have an account? <a href="<?= base_url('login') ?>">Login here</a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>

    <script>
        // Password strength indicator
        const password = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');

        if (password && strengthBar) {
            password.addEventListener('input', function() {
                const value = this.value;
                let strength = 0;

                if (value.length >= 6) strength++;
                if (value.length >= 10) strength++;
                if (/[a-z]/.test(value) && /[A-Z]/.test(value)) strength++;
                if (/[0-9]/.test(value)) strength++;
                if (/[^a-zA-Z0-9]/.test(value)) strength++;

                strengthBar.className = 'password-strength-bar';
                
                if (strength <= 2) {
                    strengthBar.classList.add('strength-weak');
                } else if (strength <= 3) {
                    strengthBar.classList.add('strength-medium');
                } else {
                    strengthBar.classList.add('strength-strong');
                }
            });
        }
    </script>
</body>
</html>
