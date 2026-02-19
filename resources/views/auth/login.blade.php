<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 100%;
            max-width: 360px;
            background: #fff;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin: 0 0 16px;
            font-size: 22px;
        }

        label {
            display: block;
            margin: 12px 0 6px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d0d5dd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .checkbox {
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .error {
            margin-top: 10px;
            color: #b42318;
            font-size: 14px;
        }

        button {
            width: 100%;
            margin-top: 16px;
            padding: 10px;
            border: 0;
            border-radius: 6px;
            background: #1d4ed8;
            color: #fff;
            cursor: pointer;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Sign in</h1>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <label for="username">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <label class="checkbox" for="remember">
                <input id="remember" type="checkbox" name="remember">
                Remember me
            </label>

            @error('username')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
