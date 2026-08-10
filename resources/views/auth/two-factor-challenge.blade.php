<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>Two-Factor Authentication</title>
    <style>
        * { box-sizing:border-box; }
        body {
            margin:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#0b0d10;
            color:#fff;
            font-family:Arial,sans-serif;
        }
        .box {
            width:100%;
            max-width:420px;
            padding:40px;
            background:#15181d;
            border:1px solid #2b3038;
            border-radius:16px;
        }
        input,button {
            width:100%;
            padding:14px;
            border-radius:8px;
        }
        input {
            background:#0e1115;
            border:1px solid #343a44;
            color:#fff;
            margin:15px 0;
        }
        button { border:0; cursor:pointer; font-weight:700; }
        .error { color:#ffb4b4; }
    </style>
</head>
<body>
<div class="box">
    <h1>Two-Factor Authentication</h1>
    <p>Enter the six-digit authentication code.</p>

    @if ($errors->any())
        <p class="error">Invalid authentication code.</p>
    @endif

    <form method="POST" action="{{ route('two-factor.login.store') }}">
        @csrf
        <input
            type="text"
            name="code"
            inputmode="numeric"
            autocomplete="one-time-code"
            maxlength="6"
            required
            autofocus
        >
        <button type="submit">Verify</button>
    </form>
</div>
</body>
</html>
