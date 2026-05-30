@php($vendorMessages = $globalVendorUnreadMessages ?? collect())

@if($vendorMessages->isNotEmpty())
    <div class="modal fade" id="vendorMessageNotificationModal" tabindex="-1" aria-labelledby="vendorMessageNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorMessageNotificationModalLabel">New Messages</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach($vendorMessages as $message)
                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="mb-1">{{ $message->subject ?: 'Message' }}</h6>
                            @if($message->sender_type === 'user')
                                <p class="mb-1"><strong>Customer:</strong> {{ $message->user?->name }} {{ $message->user?->email ? '(' . $message->user->email . ')' : '' }}</p>
                            @else
                                <p class="mb-1"><strong>Admin:</strong> Support Team</p>
                            @endif
                            @if($message->product)
                                <p class="mb-1"><strong>Product:</strong> {{ $message->product->name }}</p>
                            @endif
                            <p class="mb-2">{{ $message->message }}</p>
                            <form method="POST" action="{{ route('vendor.messages.read', $message) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-primary" type="submit">Mark Read</button>
                                <a class="btn btn-sm btn-secondary" href="{{ $message->sender_type === 'admin' ? route('vendor.support.index') : route('vendor.messages.index') }}">Open Thread</a>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
