<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceMessage;

class MessageReadController extends Controller
{
    public function vendor(MarketplaceMessage $message)
    {
        abort_unless($message->recipient_type === 'vendor' && $message->vendor_id === auth('vendor')->id(), 404);
        $message->forceFill(['read_at' => now()])->save();

        return redirect()->back()->with('status', 'Message marked as read.');
    }

    public function admin(MarketplaceMessage $message)
    {
        abort_unless($message->recipient_type === 'admin', 404);
        $message->forceFill(['read_at' => now()])->save();

        return redirect()->back()->with('status', 'Message marked as read.');
    }
}
