<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $vendors = Vendor::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backend.vendors.index', compact('vendors', 'status'));
    }

    public function show(Vendor $vendor)
    {
        return view('backend.vendors.show', compact('vendor'));
    }

    public function approve(Vendor $vendor)
    {
        $vendor->forceFill([
            'status' => 'approved',
            'kyc_status' => 'approved',
            'kyc_rejection_reason' => null,
            'kyc_reviewed_at' => now(),
            'is_active' => true,
        ])->save();

        return redirect()->route('admin.vendors.show', $vendor)->with('status', 'Vendor approved successfully.');
    }

    public function reject(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'kyc_rejection_reason' => ['required', 'string', 'max:1000'],
            'status' => ['nullable', Rule::in(['rejected', 'suspended'])],
        ]);

        $vendor->forceFill([
            'status' => $data['status'] ?? 'rejected',
            'kyc_status' => 'rejected',
            'kyc_rejection_reason' => $data['kyc_rejection_reason'],
            'kyc_reviewed_at' => now(),
        ])->save();

        return redirect()->route('admin.vendors.show', $vendor)->with('status', 'Vendor rejected successfully.');
    }
}
