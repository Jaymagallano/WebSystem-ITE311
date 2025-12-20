<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Learning Management System</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
:root {
            /* Match IntelliJ-like dark palette used in admin-theme.css */
            --primary: #3b82f6;
            --primary-dark: #1d4ed8;
            --primary-light: #60a5fa;
            --bg-body: #020617;
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --border-radius: 16px;
            --shadow-lg: 0 24px 60px rgba(15, 23, 42, 0.95);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at top, #111827 0, #020617 45%, #020617 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-main);
        }

        .register-container {
            width: 100%;
            max-width: 420px;
            /* Reduced size */
        }

.register-card {
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(148, 163, 184, 0.35);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            padding: 2rem;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 28px;
            box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.3);
            transform: rotate(5deg);
            transition: transform 0.3s ease;
        }

        .register-card:hover .register-icon {
            transform: rotate(0deg) scale(1.05);
        }

.register-header h2 {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .register-header p {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 400;
            margin: 0;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

.form-control {
            border: 1px solid rgba(55, 65, 81, 0.9);
            border-radius: 8px;
            padding: 1rem 0.75rem;
            /* Adjusted for floating labels if needed, but keeping consistent with login for now. */
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
            background-color: #020617;
            font-size: 0.95rem;
            height: calc(3.5rem + 2px);
            transition: all 0.2s ease;
        }

.form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.35);
            outline: none;
        }

        .form-floating>label {
            padding: 1rem 0.75rem;
            color: var(--text-muted);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(67, 97, 238, 0.2);
            margin-top: 0.5rem;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.3);
            color: white;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .login-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Password Strength */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin: 0.5rem 0 1.5rem;
            background: #e2e8f0;
            overflow: hidden;
            display: flex;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak {
            background: #ef476f;
            width: 33%;
        }

        .strength-medium {
            background: #fca311;
            width: 66%;
        }

        .strength-strong {
            background: #06d6a0;
            width: 100%;
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }

.alert-danger {
            background-color: rgba(127, 29, 29, 0.85);
            color: #fee2e2;
            border-left: 3px solid #ef4444;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">

                <h2>Create Account</h2>
                <p>Start your learning journey today</p>
            </div>
            <div class="register-body">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <div><?= $this->session->flashdata('error') ?></div>
                    </div>
                <?php endif; ?>

                <?= form_open('register', ['id' => 'registerForm']) ?>

                <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name" placeholder="John Doe"
                        value="<?= set_value('name') ?>" required>
                    <label for="name">Full Name</label>
                    <?= form_error('name', '<div class="text-danger small mt-1">', '</div>') ?>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                        value="<?= set_value('email') ?>" required>
                    <label for="email">Email Address</label>
                    <?= form_error('email', '<div class="text-danger small mt-1">', '</div>') ?>
                </div>

                <div class="form-floating" style="margin-bottom: 0;">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required>
                    <label for="password">Password</label>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                <?= form_error('password', '<div class="text-danger small mt-1 mb-2">', '</div>') ?>

                <div class="form-floating">
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm"
                        placeholder="Confirm Password" required>
                    <label for="password_confirm">Confirm Password</label>
                    <?= form_error('password_confirm', '<div class="text-danger small mt-1">', '</div>') ?>
                </div>

                <button type="submit" class="btn btn-register">
                    Create Account <i class="bi bi-arrow-right-short ms-1"></i>
                </button>

                <div class="login-link">
                    Already have an account? <a href="<?= base_url('login') ?>">Sign In</a>
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
            password.addEventListener('input', function () {
                const value = this.value;
                let strength = 0;

                if (value.length >= 6) strength++;
                if (value.length >= 10) strength++;
                if (/[a-z]/.test(value) && /[A-Z]/.test(value)) strength++;
                if (/[0-9]/.test(value)) strength++;
                if (/[^a-zA-Z0-9]/.test(value)) strength++;

                strengthBar.className = 'password-strength-bar';

                if (value.length > 0) {
                    if (strength <= 2) {
                        strengthBar.classList.add('strength-weak');
                    } else if (strength <= 3) {
                        strengthBar.classList.add('strength-medium');
                    } else {
                        strengthBar.classList.add('strength-strong');
                    }
                }
            });
        }
    </script>
</body>

</html>