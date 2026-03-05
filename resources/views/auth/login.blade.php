<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Licoreras</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            padding: 3rem;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 2.5rem;
        }
        
        .login-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: #666;
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .form-floating .form-control {
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            height: 60px;
        }
        
        .form-floating .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-floating label {
            padding-left: 1rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            color: white;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e0e0e0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            cursor: pointer;
        }
        
        .form-floating .form-control.password-input {
            border-radius: 12px 0 0 12px;
            border-right: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-wine-bottle"></i>
            </div>
            <h2>LICORERAS</h2>
            <p>Sistema de Control de Inventario</p>
        </div>
        
        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ $errors->first() }}
        </div>
        @endif
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
                <label for="email"><i class="fas fa-envelope me-2"></i>Correo electrónico</label>
            </div>
            
            <div class="form-floating">
                <div class="input-group">
                    <input type="password" class="form-control password-input" id="password" name="password" placeholder="Contraseña" required>
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Recordarme
                </label>
            </div>
            
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
            </button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-decoration-none">Regístrate</a>
            </small>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
