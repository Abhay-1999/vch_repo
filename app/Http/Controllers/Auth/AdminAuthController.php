<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;



class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        $adminData = Admin::get();
        return view('auth.admin.login',compact('adminData'));
    }


    public function sendOtp(Request $request)
    {
        $request->validate(['username' => 'required']);
    
        $admin = Admin::where('userid', $request->username)->first();
    
        if (!$admin || !$admin->mob_no) {
            return response()->json(['success' => false, 'message' => 'Admin not found or mobile missing.']);
        }
    
        $mobile = '91' . $admin->mob_no;
        $otp = rand(1111, 9999);
    
        Session::put('phone', $mobile);
        Session::put('admin_id', $admin->id);
        Session::put('sent_otp', $otp); // Optional fallback
    
        $templateId = env('EntertemplateID1');
        $authKey = env('AuthKeySms');
    
        $url = "https://control.msg91.com/api/v5/otp?template_id={$templateId}&mobile={$mobile}&authkey={$authKey}";
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'var1' => $otp,
        ]);

        // echo"<pre>";print_r($response->toArray());die;
    
        if ($response->failed()) {
            \Log::error('OTP Send Failed', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['success' => false, 'message' => 'Failed to send OTP.']);
        }
    
        return response()->json(['success' => true, 'message' => 'OTP sent to admin mobile.']);
    }
    
    public function verifyOtp($mobile, $otp)
    {
        $url = "https://control.msg91.com/api/v5/otp/verify?otp={$otp}&mobile={$mobile}";
        $response = Http::withHeaders([
            'authkey' => env('AuthKeySms'),
        ])->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        \Log::error('OTP Verify Failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return [
            'type' => 'error',
            'message' => 'OTP verification failed.',
        ];
    }

    public function otpSubmit(Request $request)
    {
        $request->validate(['otp' => 'required|digits:4']);
    
        $mobile = Session::get('phone');
        $adminId = Session::get('admin_id');
    
        if (!$mobile || !$adminId) {
            return response()->json(['success' => false, 'message' => 'Session expired. Try again.']);
        }
    
        $otpResult = $this->verifyOtp($mobile, $request->otp);
    
        if (isset($otpResult['type']) && $otpResult['type'] === 'success') {
            $admin = Admin::find($adminId);
            Auth::guard('admin')->login($admin); // 👈 use guard
    
            Session::forget(['phone', 'admin_id', 'sent_otp']);
    
            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'redirect' => url('/admin/dashboard')
            ]);
        }
    
        return response()->json(['success' => false, 'message' => $otpResult['message'] ?? 'Invalid OTP']);
    }
    
    // public function submit(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
    //         return redirect()->intended('admin/dashboard');
    //     }

    //     return back()->withErrors(['email' => 'Invalid credentials']);
    // }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}