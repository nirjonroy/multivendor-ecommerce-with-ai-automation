<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\MarketplaceMessage;
use Illuminate\Http\Request;

class SupportMessageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        MarketplaceMessage::create([
            'vendor_id' => auth('vendor')->id(),
            'admin_id' => Admin::query()->where('is_active', true)->value('id'),
            'sender_type' => 'vendor',
            'recipient_type' => 'admin',
            'subject' => $data['subject'] ?: 'Vendor support request',
            'message' => $data['message'],
        ]);

        return redirect()->back()->with('status', 'Support message sent to admin.');
    }
}
