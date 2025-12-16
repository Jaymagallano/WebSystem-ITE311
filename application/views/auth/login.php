<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learning Management System</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            /* Warm aesthetic gradient fallback */
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            /* Dynamic gradient */
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            perspective: 1000px;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .login-header {
            padding: 40px 30px 20px;
            text-align: center;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 36px;
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
            transform: rotate(-5deg);
        }

        .login-header h2 {
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #666;
            font-weight: 400;
        }

        .login-body {
            padding: 20px 40px 40px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-control {
            border: 2px solid #eef2f6;
            border-radius: 12px;
            padding: 1rem 0.75rem;
            background-color: #f8fafc;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .btn-login {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(67, 97, 238, 0.35);
            color: white;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .register-link a {
            color: #4361ee;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Alert Styling */
        .alert {
            border-radius: 12px;
            font-size: 14px;
            border: none;
            margin-bottom: 25px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="bi bi- Mortarboard-fill"></i>
                </div>
                <h2>Welcome Back</h2>
                <p>Sign in to access your dashboard</p>
            </div>

            <div class="login-body">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <div><?= $this->session->flashdata('error') ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= $this->session->flashdata('success') ?></div>
                    </div>
                <?php endif; ?>

                <?= form_open('login', ['id' => 'loginForm']) ?>

                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                        value="<?= set_value('email') ?>" required>
                    <label for="email">Email Address</label>
                    <?= form_error('email', '<div class="text-danger small mt-1">', '</div>') ?>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required>
                    <label for="password">Password</label>
                    <?= form_error('password', '<div class="text-danger small mt-1">', '</div>') ?>
                </div>

                <button type="submit" class="btn btn-login">
                    Sign In <i class="bi bi-arrow-right-short ms-1"></i>
                </button>

                <div class="register-link">
                    New here? <a href="<?= base_url('register') ?>">Create an Account</a>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</body>

</html>