<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ATMI MIKAEL FORTUNA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 950px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .login-left {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .login-left .logo {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .login-left .subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .login-left .features {
            position: relative;
            z-index: 1;
        }

        .login-left .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .login-left .feature-item i {
            font-size: 1.5rem;
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 10px;
        }

        .login-right {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .login-right .welcome-text {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .input-icon .form-control {
            padding-left: 45px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .demo-accounts {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .demo-accounts h6 {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 10px;
        }

        .demo-item {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 8px;
            padding: 8px;
            background: #f8fafc;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .demo-item:hover {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .demo-item code {
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            border: 1px solid #e2e8f0;
        }

        .demo-item .role-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-right: 5px;
        }

        .role-admin {
            background: #fee2e2;
            color: #991b1b;
        }

        .role-operator {
            background: #dbeafe;
            color: #1e40af;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            .login-left {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side -->
        <div class="login-left">
            <div class="logo">ATMI MIKAEL FORTUNA</div>
            <div class="subtitle">Inventory System</div>
            
            <div class="features">
                <div class="feature-item">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>Monitoring Stok Material Real-time</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Laporan Produksi 3 Shift</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>Warning System Otomatis</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-calculator-fill"></i>
                    <span>Kalkulasi Kebutuhan Material</span>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="login-right">
            <h2>Selamat Datang 👋</h2>
            <p class="welcome-text">Silakan login untuk mengakses sistem</p>

            <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i><?php echo e($errors->first()); ?>

            </div>
            <?php endif; ?>

            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-icon">
                        <i class="bi bi-envelope-fill"></i>
                        <input type="email" name="email" class="form-control" 
                               value="<?php echo e(old('email')); ?>" placeholder="nama@atmi.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-icon">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember" style="font-size: 0.85rem;">
                                Ingat saya
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <div class="demo-accounts">
                <h6><i class="bi bi-info-circle me-1"></i>Klik untuk auto-fill:</h6>
                
                <div class="demo-item" onclick="fillLogin('admin@atmi.com', 'password123')">
                    <span class="role-badge role-admin">ADMIN</span>
                    <code>admin@atmi.com</code> / <code>password123</code>
                </div>
                
                <div class="demo-item" onclick="fillLogin('operator@atmi.com', 'operator123')">
                    <span class="role-badge role-operator">OPERATOR</span>
                    <code>operator@atmi.com</code> / <code>operator123</code>
                </div>
                
               

    <script>
        function fillLogin(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
            
            // Visual feedback
            const items = document.querySelectorAll('.demo-item');
            items.forEach(item => item.style.background = '#f8fafc');
            event.currentTarget.style.background = '#e0e7ff';
        }
    </script>
</body>
</html><?php /**PATH C:\laragon\www\atmi-inventory\resources\views/auth/login.blade.php ENDPATH**/ ?>