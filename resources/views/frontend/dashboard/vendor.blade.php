<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Vendor Dashboard'])
</head>
<body>
@include('frontend.partials.header')
<section class="section-big-py-space bg-light">
    <div class="custom-container">
        <h2>Vendor Dashboard</h2>
        <p>Welcome, {{ auth('vendor')->user()->name }}.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-normal" type="submit">Logout</button>
        </form>
    </div>
</section>
@include('frontend.partials.footer')
</body>
</html>
