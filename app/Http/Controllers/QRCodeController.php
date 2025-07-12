<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generateQRCode()
    {
        // Replace this with your website link
        $websiteLink = 'http://vch.thesimplyindia.com/';

        // Generate the QR code
        $qrCode = QrCode::size(300)->generate($websiteLink);

        // Return the QR code as a response
        return response($qrCode)->header('Content-type', 'image/png');
    }

        public function showQRCode()
    {


        
        // Optional: Base64 encode to make it URL-safe

        
        // Generate QR code that contains your URL and data

        
        $websiteLink = 'http://vch.thesimplyindia.com';
        
        $qrCode = QrCode::size(300)->generate($websiteLink);

        return view('qr_code', compact('qrCode'));
    }
}