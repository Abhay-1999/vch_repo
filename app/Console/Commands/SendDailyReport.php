<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Mail\DailyReportMail;

class SendDailyReport extends Command
{
    protected $signature = 'report:send-daily';
    protected $description = 'Generate and send daily restaurant PDF report at 11:50 PM';

    public function handle()
    {
        // --- 1️⃣ Set date range (today) ---
        $startDate = date('Y-m-d');
        $endDate   = date('Y-m-d');

        $group_code = '01';
        $rest_code  = '01';

        // --- 2️⃣ Get restaurant info ---
        $rest_data = DB::table('chain_master')
            ->where('group_code', $group_code)
            ->where('rest_code', $rest_code)
            ->first();

        // --- 3️⃣ Query daily totals ---
        $data = DB::table('order_hd')
            ->select('tran_date', 'payment_mode', DB::raw('SUM(paid_amt) as total_net_amt'))
            ->whereBetween('tran_date', [$startDate, $endDate])
            ->where('order_hd.flag', '!=', 'H')
            ->groupBy('tran_date', 'payment_mode')
            ->orderBy('tran_date')
            ->get();

        // --- 4️⃣ Prepare totals per mode ---
        $totals = [];
        foreach ($data as $d) {
            $date = date('d-m-Y', strtotime($d->tran_date));
            if (!isset($totals[$date])) {
                $totals[$date] = [
                    'cash'   => 0,
                    'UPI'    => 0,
                    'Online' => 0,
                    'Zomato' => 0,
                    'Swiggy' => 0,
                ];
            }

            switch ($d->payment_mode) {
                case 'C': $totals[$date]['cash']   += $d->total_net_amt; break;
                case 'O': $totals[$date]['Online'] += $d->total_net_amt; break;
                case 'U': $totals[$date]['UPI']    += $d->total_net_amt; break;
                case 'Z': $totals[$date]['Zomato'] += $d->total_net_amt; break;
                case 'S': $totals[$date]['Swiggy'] += $d->total_net_amt; break;
            }
        }

        // --- 5️⃣ Prepare view data for PDF ---
        $viewData = [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'totals'    => $totals,
            'rest_data' => $rest_data,
        ];

        // --- 6️⃣ Generate PDF ---
        $pdf = Pdf::loadView('reports.dailyReport', $viewData)
                            ->setPaper('a4', 'portrait')
                            ->setOptions(['dpi' => 150, 'defaultFont' => 'DejaVu Sans'])
                            ->output();
        $filename = 'daily-report-' . Carbon::now('Asia/Kolkata')->format('Ymd') . '.pdf';

        // --- 7️⃣ Prepare mail content ---
        $subject  = "Daily Report - " . Carbon::now('Asia/Kolkata')->toFormattedDateString();
        $bodyText = " ";


        // --- 8️⃣ Recipients ---
        $recipients = ['abhayksc1999@gmail.com'];

        // --- 9️⃣ Send mail with PDF attachment ---
        foreach ($recipients as $to) {
            Mail::to($to)->send(new DailyReportMail($subject, $bodyText, $pdf, $filename));
        }

        // --- 🔟 Console confirmation ---
        $this->info("✅ Daily PDF report sent successfully to: " . implode(', ', $recipients));
    }
}
