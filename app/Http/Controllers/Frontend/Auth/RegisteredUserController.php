<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('frontend.auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'shop_name' => ['nullable', 'required_if:account_type,vendor', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'account_type' => ['required', 'in:user,vendor'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->validate([
            'email' => ['unique:' . ($data['account_type'] === 'vendor' ? 'vendors' : 'users') . ',email'],
        ]);

        if ($data['account_type'] === 'vendor') {
            $vendor = Vendor::create([
                'name' => $data['name'],
                'shop_name' => $data['shop_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
            ]);

            Auth::guard('vendor')->login($vendor);

            return redirect()->route('vendor.dashboard');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
