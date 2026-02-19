<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
            min-height: 100vh;
        }

        .wrap {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        button {
            padding: 8px 14px;
            border: 0;
            border-radius: 6px;
            background: #dc2626;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <h1>Exchange Office Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>

        <p>Welcome, {{ auth()->user()->name }} ({{ auth()->user()->username }})</p>
    </div>
</body>
</html>
