<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
</head>
<body>
    <h1>Payment Successful</h1>
    <p>Your payment was successful. Thank you!</p>
    <!-- You can add any additional content or redirection logic here -->

    <!-- @if (Auth::check())
        <a href="{{ route('logout') }}" >Logout</a>
    @endif -->

    <!-- @if (Auth::guest())
        <a href="{{ route('login') }}" >Login</a>
    @endif -->
    @if (Auth::check())
    <a href="/home">Go back to HomePage </a>
    @endif
</body>
</html>
