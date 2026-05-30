<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $messages = MarketplaceMessage::with(['vendor', 'product'])
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('sender_type', 'vendor')->where('recipient_type', 'admin');
                })->orWhere(function ($query) {
                    $query->where('sender_type', 'admin')->where('recipient_type', 'vendor');
                });
            })
            ->when($status === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->when($status === 'read', fn ($query) => $query->whereNotNull('read_at'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backend.messages.index', compact('messages', 'status'));
    }

    public function reply(Request $request, MarketplaceMessage $message)
    {
        abort_unless($message->vendor_id, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        MarketplaceMessage::create([
            'vendor_id' => $message->vendor_id,
            'admin_id' => auth('admin')->id(),
            'sender_type' => 'admin',
            'recipient_type' => 'vendor',
            'subject' => $message->subject,
            'message' => $data['message'],
        ]);

        return redirect()->route('admin.messages.index')->with('status', 'Reply sent to vendor.');
    }
}
