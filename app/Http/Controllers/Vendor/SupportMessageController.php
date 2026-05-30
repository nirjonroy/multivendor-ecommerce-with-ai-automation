<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\MarketplaceMessage;
use Illuminate\Http\Request;

class SupportMessageController extends Controller
{
    public function index()
    {
        $vendor = auth('vendor')->user();
        $supportMessages = MarketplaceMessage::query()
            ->where('vendor_id', $vendor->id)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('sender_type', 'vendor')->where('recipient_type', 'admin');
                })->orWhere(function ($query) {
                    $query->where('sender_type', 'admin')->where('recipient_type', 'vendor');
                });
            })
            ->latest()
            ->paginate(15);

        return view('vendor.support.index', compact('vendor', 'supportMessages'));
    }

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

    public function reply(Request $request, MarketplaceMessage $message)
    {
        abort_unless($message->vendor_id === auth('vendor')->id(), 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        MarketplaceMessage::create([
            'vendor_id' => auth('vendor')->id(),
            'admin_id' => $message->admin_id,
            'sender_type' => 'vendor',
            'recipient_type' => 'admin',
            'subject' => $message->subject,
            'message' => $data['message'],
        ]);

        return redirect()->route('vendor.support.index')->with('status', 'Reply sent to admin.');
    }
}
