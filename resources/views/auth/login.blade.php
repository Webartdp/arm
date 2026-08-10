<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>Secure Access</title>
    <style>
        * { box-sizing: border-box; }
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
        h1 { margin:0 0 28px; font-size:25px; }
        label { display:block; margin:18px 0 7px; color:#b8bec8; }
        input {
            width:100%;
            padding:13px 14px;
            border-radius:8px;
            border:1px solid #343a44;
            background:#0e1115;
            color:#fff;
        }
        button {
            width:100%;
            margin-top:24px;
            padding:14px;
            border:0;
            border-radius:8px;
            cursor:pointer;
            font-weight:700;
        }
        .error {
            padding:12px;
            margin-bottom:15px;
            border-radius:8px;
            background:#35181a;
            color:#ffb4b4;
        }
    </style>
</head>
<body>
<div class="box">
    <h1>Secure Access</h1>

    @if ($errors->any())
        <div class="error">Authentication failed.</div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label>Email</label>
        <input
            type="email"
            name="email"
            autocomplete="username"
            required
            autofocus
        >

        <label>Password</label>
        <input
            type="password"
            name="password"
            autocomplete="current-password"
            required
        >

        <button type="submit">Continue</button>
    </form>
</div>
</body>
</html>
