<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceMessage;
use Illuminate\Http\Request;

class UserMessageController extends Controller
{
    public function index()
    {
        $messages = MarketplaceMessage::with(['vendor', 'product'])
            ->where('user_id', auth()->id())
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('sender_type', 'user')->where('recipient_type', 'vendor');
                })->orWhere(function ($query) {
                    $query->where('sender_type', 'vendor')->where('recipient_type', 'user');
                });
            })
            ->latest()
            ->paginate(15);

        return view('frontend.messages.index', compact('messages'));
    }

    public function reply(Request $request, MarketplaceMessage $message)
    {
        abort_unless($message->user_id === auth()->id() && $message->vendor_id, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        MarketplaceMessage::create([
            'product_id' => $message->product_id,
            'user_id' => auth()->id(),
            'vendor_id' => $message->vendor_id,
            'sender_type' => 'user',
            'recipient_type' => 'vendor',
            'subject' => $message->subject,
            'message' => $data['message'],
        ]);

        return redirect()->route('messages.index')->with('status', 'Reply sent to vendor.');
    }
}
