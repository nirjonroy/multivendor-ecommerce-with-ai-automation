@php($adminMessages = $globalAdminUnreadMessages ?? collect())

@if($adminMessages->isNotEmpty())
    <div class="modal fade" id="adminMessageNotificationModal" tabindex="-1" role="dialog" aria-labelledby="adminMessageNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminMessageNotificationModalLabel">New Vendor Support Messages</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @foreach($adminMessages as $message)
                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="mb-1">{{ $message->subject ?: 'Vendor support request' }}</h6>
                            <p class="mb-1"><strong>Vendor:</strong> {{ $message->vendor?->shop_name ?: $message->vendor?->name }}</p>
                            <p class="mb-2">{{ $message->message }}</p>
                            <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-primary" type="submit">Mark Read</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
