<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Customer Dashboard'])
</head>
<body>
@include('frontend.partials.header')
<section class="section-big-py-space bg-light">
    <div class="custom-container">
        <h2>Customer Dashboard</h2>
        <p>Welcome, {{ auth()->user()->name }}.</p>
        <a href="{{ route('messages.index') }}" class="btn btn-normal mb-3">Messages @if(($globalUserUnreadMessages ?? collect())->count() > 0)({{ ($globalUserUnreadMessages ?? collect())->count() }})@endif</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-normal" type="submit">Logout</button>
        </form>
    </div>
</section>
@include('frontend.partials.footer')
</body>
</html>
