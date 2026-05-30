<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceMessage;
use Illuminate\Http\Request;

class CustomerMessageController extends Controller
{
    public function index()
    {
        $vendor = auth('vendor')->user();

        $messages = MarketplaceMessage::with(['user', 'product'])
            ->where('vendor_id', $vendor->id)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('sender_type', 'user')->where('recipient_type', 'vendor');
                })->orWhere(function ($query) {
                    $query->where('sender_type', 'vendor')->where('recipient_type', 'user');
                });
            })
            ->latest()
            ->paginate(15);

        return view('vendor.messages.index', compact('messages'));
    }

    public function reply(Request $request, MarketplaceMessage $message)
    {
        abort_unless($message->vendor_id === auth('vendor')->id() && $message->user_id, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        MarketplaceMessage::create([
            'product_id' => $message->product_id,
            'user_id' => $message->user_id,
            'vendor_id' => auth('vendor')->id(),
            'sender_type' => 'vendor',
            'recipient_type' => 'user',
            'subject' => $message->subject,
            'message' => $data['message'],
        ]);

        return redirect()->route('vendor.messages.index')->with('status', 'Reply sent to customer.');
    }
}
