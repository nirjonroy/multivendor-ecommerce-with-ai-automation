@php($userMessages = $globalUserUnreadMessages ?? collect())

@if($userMessages->isNotEmpty())
    <div class="modal fade" id="userMessageNotificationModal" tabindex="-1" role="dialog" aria-labelledby="userMessageNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userMessageNotificationModalLabel">New Vendor Messages</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @foreach($userMessages as $message)
                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="mb-1">{{ $message->subject ?: 'Vendor message' }}</h6>
                            <p class="mb-1"><strong>Vendor:</strong> {{ $message->vendor?->shop_name ?: $message->vendor?->name }}</p>
                            @if($message->product)
                                <p class="mb-1"><strong>Product:</strong> {{ $message->product->name }}</p>
                            @endif
                            <p class="mb-2">{{ $message->message }}</p>
                            <form method="POST" action="{{ route('messages.read', $message) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-normal" type="submit">Mark Read</button>
                                <a class="btn btn-sm btn-secondary" href="{{ route('messages.index') }}">Open Messages</a>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
