<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administration</title>
</head>

<body>
    <h1>Administration</h1>

    <p>
        Secure administrator session active.
    </p>

    <p>
        Two-factor authentication: enabled
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
