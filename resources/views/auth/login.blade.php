<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212; /* Color de fondo oscuro */
            color: #fff; /* Color de texto claro */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            background-color: #333; /* Color de fondo para la tarjeta */
            border-radius: 10px;
            box-shadow: 0px 10px 20px 0px rgba(0, 0, 0, 0.1);
            width: 400px;
            padding: 40px;
            box-sizing: border-box;
        }

        .card-header {
            font-size: 24px;
            font-weight: bold;
            color: #fff; /* Color de texto claro */
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 16px;
            font-weight: bold;
            color: #ccc; /* Color de texto gris claro */
            display: block;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #555; /* Borde más oscuro */
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #444; /* Color de fondo del campo */
            color: #fff; /* Color de texto claro */
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
        }

        .invalid-feedback {
            color: red;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Transición de color al pasar el mouse */
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-link {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <section style="display:flex; justify-content: center">
        <img src="{{asset('vendor/adminlte/dist/img/logo.png')}}" alt="" style="width: 130px">
    </section>
    <form method="POST" action="{{ route('login') }}" style="margin-top: 10px">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">{{ __('Usuario') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="...">
        
        </div>

        <div class="form-group">
            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="...">
        
        </div>
        <div style="display: flex; justify-content: center">
            @error('email')
                <span class="invalid-feedback mt-4" role="alert">Datos incorrectos</span>
            @enderror
            @error('password')
                <span class="invalid-feedback mt-4" role="alert">Datos incorrectos</span>
            @enderror
        </div>

        <div class="form-group" style="margin-top: 4px">
            <button type="submit" class="btn btn-primary">{{ __('Ingresar') }}</button>
        </div>
    </form>
</div>

</body>
</html>
