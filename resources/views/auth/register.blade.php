<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Licoreras</title>
    
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
        
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            padding: 3rem;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header .logo {
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
        
        .register-header h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .register-header p {
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
        
        .btn-register {
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
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="logo">
                <i class="fas fa-wine-bottle"></i>
            </div>
            <h2>LICORERAS</h2>
            <p>Crear nueva cuenta</p>
        </div>
        
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="form-floating">
                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre completo" value="{{ old('name') }}" required autofocus>
                <label for="name"><i class="fas fa-user me-2"></i>Nombre completo</label>
            </div>
            
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required>
                <label for="email"><i class="fas fa-envelope me-2"></i>Correo electrónico</label>
            </div>
            
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                <label for="password"><i class="fas fa-lock me-2"></i>Contraseña</label>
            </div>
            
            <div class="form-floating">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña" required>
                <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Confirmar contraseña</label>
            </div>
            
            <button type="submit" class="btn btn-register">
                <i class="fas fa-user-plus me-2"></i>Registrarse
            </button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-decoration-none">Iniciar sesión</a>
            </small>
        </div>
    </div>
</body>
</html>
