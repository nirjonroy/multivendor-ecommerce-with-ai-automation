@extends('vendor.layouts.app', ['title' => 'Messages'])

@section('page_title', 'Messages')

@section('content')
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="card">
        <div class="card-header"><h3 class="card-title">Customer Messages</h3></div>
        <div class="card-body">
            @forelse($messages as $message)
                <div class="border-bottom pb-3 mb-3">
                    <h5>{{ $message->subject ?: 'Customer message' }}</h5>
                    <p class="mb-1"><strong>{{ ucfirst($message->sender_type) }}:</strong> {{ $message->message }}</p>
                    <p class="mb-1"><strong>Customer:</strong> {{ $message->user?->name }} {{ $message->user?->email ? '(' . $message->user->email . ')' : '' }}</p>
                    @if($message->product)
                        <p class="mb-1"><strong>Product:</strong> {{ $message->product->name }}</p>
                    @endif
                    <small>{{ $message->created_at?->format('d M Y h:i A') }} / {{ $message->read_at ? 'Read' : 'Unread' }}</small>

                    @if($message->recipient_type === 'vendor' && ! $message->read_at)
                        <form method="POST" action="{{ route('vendor.messages.read', $message) }}" class="d-inline ms-2">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-primary" type="submit">Mark Read</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('vendor.messages.reply', $message) }}" class="mt-3">
                        @csrf
                        <textarea class="form-control mb-2" name="message" rows="3" required placeholder="Reply to customer"></textarea>
                        <button class="btn btn-primary" type="submit">Reply</button>
                    </form>
                </div>
            @empty
                <p>No customer messages yet.</p>
            @endforelse
            {{ $messages->links() }}
        </div>
    </div>
@endsection
