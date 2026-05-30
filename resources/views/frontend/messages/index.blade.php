<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.partials.head', ['title' => 'Messages'])
</head>
<body>
@include('frontend.partials.header')
<section class="breadcrumb-main bg-light">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <div>
                <h2>Messages</h2>
                <ul><li><a href="{{ route('home') }}">home</a></li><li><i class="fa fa-angle-double-right"></i></li><li><a>messages</a></li></ul>
            </div>
        </div>
    </div>
</section>
<section class="section-big-py-space bg-light">
    <div class="custom-container">
        @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        <div class="theme-card">
            <h3>Vendor Messages</h3>
            @forelse($messages as $message)
                <div class="border-bottom py-3">
                    <h5>{{ $message->subject ?: 'Vendor message' }}</h5>
                    <p class="mb-1"><strong>{{ ucfirst($message->sender_type) }}:</strong> {{ $message->message }}</p>
                    <p class="mb-1"><strong>Vendor:</strong> {{ $message->vendor?->shop_name ?: $message->vendor?->name }}</p>
                    @if($message->product)
                        <p class="mb-1"><strong>Product:</strong> <a href="{{ route('products.show', $message->product) }}">{{ $message->product->name }}</a></p>
                    @endif
                    <small>{{ $message->created_at?->format('d M Y h:i A') }} / {{ $message->read_at ? 'Read' : 'Unread' }}</small>

                    @if($message->recipient_type === 'user' && ! $message->read_at)
                        <form method="POST" action="{{ route('messages.read', $message) }}" class="d-inline ml-2">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-normal" type="submit">Mark Read</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('messages.reply', $message) }}" class="mt-3">
                        @csrf
                        <textarea class="form-control mb-2" name="message" rows="3" required placeholder="Reply to vendor"></textarea>
                        <button class="btn btn-normal" type="submit">Reply</button>
                    </form>
                </div>
            @empty
                <p>No messages yet.</p>
            @endforelse
            {{ $messages->links() }}
        </div>
    </div>
</section>
@include('frontend.partials.footer')
</body>
</html>
