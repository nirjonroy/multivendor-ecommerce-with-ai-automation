@extends('backend.layouts.app')

@section('title', 'Messages - Admin')
@section('page_title', 'Messages')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.messages.index') }}">All</a>
                <a class="btn btn-sm {{ $status === 'unread' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.messages.index', ['status' => 'unread']) }}">Unread</a>
                <a class="btn btn-sm {{ $status === 'read' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('admin.messages.index', ['status' => 'read']) }}">Read</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordernone">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Vendor</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>{{ $message->subject ?: 'Vendor support request' }}</td>
                                <td>{{ $message->vendor?->shop_name ?: $message->vendor?->name ?: 'Vendor' }}<br><small>{{ $message->vendor?->email }}</small></td>
                                <td style="max-width:360px">{{ $message->message }}</td>
                                <td>
                                    @if($message->recipient_type === 'admin')
                                        <span class="badge badge-{{ $message->read_at ? 'secondary' : 'primary' }}">{{ $message->read_at ? 'Read' : 'Unread' }}</span>
                                    @else
                                        <span class="badge badge-info">Sent to Vendor</span>
                                    @endif
                                </td>
                                <td>{{ $message->created_at?->format('d M Y h:i A') }}</td>
                                <td class="text-right">
                                    @if($message->recipient_type === 'admin' && ! $message->read_at)
                                        <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-primary" type="submit">Mark Read</button>
                                        </form>
                                    @elseif($message->recipient_type === 'admin')
                                        <span class="text-muted">Done</span>
                                    @endif
                                    <button class="btn btn-sm btn-secondary mt-2" type="button" data-toggle="collapse" data-target="#admin-reply-{{ $message->id }}">Reply</button>
                                    <form id="admin-reply-{{ $message->id }}" class="collapse mt-2" method="POST" action="{{ route('admin.messages.reply', $message) }}">
                                        @csrf
                                        <textarea class="form-control mb-2" name="message" rows="3" required placeholder="Reply to vendor"></textarea>
                                        <button class="btn btn-sm btn-primary" type="submit">Send Reply</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No messages found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $messages->links() }}
        </div>
    </div>
@endsection
