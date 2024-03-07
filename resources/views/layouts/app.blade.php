<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://js.stripe.com/v3/"></script>
   
</head>
<body>
    <div id="app">
                <nav class="navbar navbar-dark bg-primary">
                <h3> Laravel Test </h3>
            </nav>
    
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
