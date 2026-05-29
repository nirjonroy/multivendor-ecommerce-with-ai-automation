<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShopSettingsController extends Controller
{
    public function edit()
    {
        return view('vendor.shop-settings.edit', [
            'vendor' => auth('vendor')->user(),
        ]);
    }

    public function update(Request $request)
    {
        $vendor = auth('vendor')->user();

        $data = $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_slug' => ['nullable', 'string', 'max:255', Rule::unique('vendors', 'shop_slug')->ignore($vendor->id)],
            'shop_email' => ['nullable', 'email', 'max:255'],
            'shop_phone' => ['nullable', 'string', 'max:50'],
            'shop_address' => ['nullable', 'string'],
            'shop_description' => ['nullable', 'string'],
            'kyc_business_type' => ['nullable', 'string', 'max:100'],
            'kyc_business_registration_number' => ['nullable', 'string', 'max:100'],
            'kyc_tax_id' => ['nullable', 'string', 'max:100'],
            'kyc_nid_number' => ['nullable', 'string', 'max:100'],
            'kyc_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $data['shop_slug'] = $data['shop_slug'] ?: Str::slug($data['shop_name']);
        unset($data['kyc_document']);

        if ($request->hasFile('kyc_document')) {
            $data['kyc_document_path'] = $request->file('kyc_document')->store('vendor-kyc', 'public');
        }

        $hasKycData = collect([
            $data['kyc_business_type'] ?? $vendor->kyc_business_type,
            $data['kyc_business_registration_number'] ?? $vendor->kyc_business_registration_number,
            $data['kyc_tax_id'] ?? $vendor->kyc_tax_id,
            $data['kyc_nid_number'] ?? $vendor->kyc_nid_number,
            $data['kyc_document_path'] ?? $vendor->kyc_document_path,
        ])->filter()->isNotEmpty();

        if ($hasKycData && in_array($vendor->kyc_status, ['not_submitted', 'rejected'], true)) {
            $data['kyc_status'] = 'submitted';
            $data['kyc_submitted_at'] = now();
            $data['kyc_rejection_reason'] = null;

            if ($vendor->status === 'rejected') {
                $data['status'] = 'pending';
            }
        }

        $vendor->fill($data);
        $vendor->save();

        return redirect()->route('vendor.shop-settings.edit')->with('status', 'Shop settings updated successfully.');
    }
}
