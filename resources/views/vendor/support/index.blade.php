@extends('vendor.layouts.app', ['title' => 'Support'])

@section('page_title', 'Support')

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        <div class="col-lg-5">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header"><h3 class="card-title">Contact Admin</h3></div>
                <form method="POST" action="{{ route('vendor.support-message.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Support subject">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="6" required placeholder="Write your message to admin">{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary" type="submit">Send Support Message</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Support History</h3></div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>From</th>
                                <th>Status</th>
                                <th>Sent</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supportMessages as $message)
                                <tr>
                                    <td>{{ $message->subject ?: 'Support request' }}</td>
                                    <td style="max-width:360px">{{ $message->message }}</td>
                                    <td>{{ ucfirst($message->sender_type) }}</td>
                                    <td><span class="badge text-bg-{{ $message->read_at ? 'success' : 'warning' }}">{{ $message->read_at ? 'Read' : 'Unread' }}</span></td>
                                    <td>{{ $message->created_at?->format('d M Y h:i A') }}</td>
                                    <td>
                                        @if($message->recipient_type === 'vendor' && ! $message->read_at)
                                            <form method="POST" action="{{ route('vendor.messages.read', $message) }}" class="mb-2">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-primary" type="submit">Mark Read</button>
                                            </form>
                                        @endif
                                        <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#vendor-support-reply-{{ $message->id }}">Reply</button>
                                        <form id="vendor-support-reply-{{ $message->id }}" class="collapse mt-2" method="POST" action="{{ route('vendor.support.reply', $message) }}">
                                            @csrf
                                            <textarea class="form-control mb-2" name="message" rows="3" required placeholder="Reply to admin"></textarea>
                                            <button class="btn btn-sm btn-primary" type="submit">Send</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4">No support messages yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($supportMessages->hasPages())
                    <div class="card-footer">{{ $supportMessages->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
