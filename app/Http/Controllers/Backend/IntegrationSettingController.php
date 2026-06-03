<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class IntegrationSettingController extends Controller
{
    public function edit()
    {
        return view('backend.settings.integrations', [
            'n8nProductWebhook' => Setting::get('n8n_product_webhook'),
            'n8nOrderWebhook' => Setting::get('n8n_order_webhook'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'n8n_product_webhook' => ['nullable', 'url', 'max:1000'],
            'n8n_order_webhook' => ['nullable', 'url', 'max:1000'],
        ]);

        Setting::setMany([
            'n8n_product_webhook' => $data['n8n_product_webhook'] ?? null,
            'n8n_order_webhook' => $data['n8n_order_webhook'] ?? null,
        ]);

        return back()->with('status', 'Integration settings updated successfully.');
    }
}
