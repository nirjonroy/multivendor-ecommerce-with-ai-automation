<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    private array $sections = ['account', 'addresses', 'orders', 'wishlist', 'newsletter', 'password'];

    public function index(Request $request, ?string $section = 'account')
    {
        $section = in_array($section, $this->sections, true) ? $section : 'account';
        $orders = $request->user()->orders()->with('items.product')->latest()->get();

        return view('frontend.dashboard.user', [
            'section' => $section,
            'orders' => $orders,
            'wishlistProducts' => $request->user()->wishlistProducts()->latest('wishlists.created_at')->get(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $request->user()->update($data);

        return redirect()->route('dashboard.section', 'account')->with('status', 'Account information updated.');
    }

    public function updateAddresses(Request $request)
    {
        $data = $request->validate([
            'billing_address' => ['nullable', 'string', 'max:1000'],
            'shipping_address' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->update($data);

        return redirect()->route('dashboard.section', 'addresses')->with('status', 'Address book updated.');
    }

    public function updateNewsletter(Request $request)
    {
        $request->user()->update([
            'newsletter_subscribed' => $request->boolean('newsletter_subscribed'),
        ]);

        return redirect()->route('dashboard.section', 'newsletter')->with('status', 'Newsletter preference updated.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('dashboard.section', 'password')->with('status', 'Password changed successfully.');
    }
}
