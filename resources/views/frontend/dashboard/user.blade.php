<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/color2.css') }}">
</head>
<body>
<section class="section-big-py-space bg-light">
    <div class="custom-container">
        <h2>Customer Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-normal" type="submit">Logout</button>
        </form>
    </div>
</section>
</body>
</html>
