<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationController extends Controller
{
    
    private $merchantId = "HDFC000000079973"; // replace with your Merchant ID
    private $merchantVpa = "vijaychathouse@hdfcbank"; // replace with your Merchant VPA
    private $merchantName = "VIJAY CHAT HOUSE"; // replace with your Merchant ID
    private $merchantKey = "799abcd43f85c9e68f25fc29d0db73e0"; // replace with your AES Key
    private $uatUrl = "https://testupi.mindgate.in:8443/hupi/mePayInetentReq";
    private $prodUrl = "https://upiv2.hdfcbank.com/upi/";

    

    public function TableReservationForm()
    {
        // echo"<pre>";die;
        $admin = Auth::guard('admin')->user();
        $role = $admin->role;
          return view('table_reservation.tables'); // Pass orders
    }



}
