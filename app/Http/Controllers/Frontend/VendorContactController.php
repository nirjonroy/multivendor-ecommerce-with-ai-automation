<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceMessage;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorContactController extends Controller
{
    public function store(Request $request, Product $product)
    {
        abort_unless($product->owner_type === 'vendor' && $product->vendor_id, 404);

        $data = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        MarketplaceMessage::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'vendor_id' => $product->vendor_id,
            'sender_type' => 'user',
            'recipient_type' => 'vendor',
            'subject' => $data['subject'] ?: 'Product inquiry: ' . $product->name,
            'message' => $data['message'],
        ]);

        return redirect()->route('products.show', $product)->with('status', 'Message sent to vendor.');
    }
}
