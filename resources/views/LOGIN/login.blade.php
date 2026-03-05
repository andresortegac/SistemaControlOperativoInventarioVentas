<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <form method="POST" action="/login">
        @csrf

        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>

        <button type="submit">Entrar</button>
    </form>

    @error('email')
        <p style="color:red">{{ $message }}</p>
    @enderror
</body>
</html>
