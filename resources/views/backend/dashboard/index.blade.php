<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
<div class="page-wrapper">
    <div class="page-body-wrapper">
        <div class="page-body p-4">
            <div class="container-fluid">
                <h3>Admin Dashboard</h3>
                <p>Welcome, {{ auth('admin')->user()->name }}.</p>
                <a href="{{ asset('backend/index.html') }}" class="btn btn-primary" target="_blank">Open HTML Admin Design</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline-block ml-2">
                    @csrf
                    <button class="btn btn-outline-secondary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
