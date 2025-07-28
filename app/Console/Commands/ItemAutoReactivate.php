<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class ItemAutoReactivate extends Command
{
    protected $signature = 'item:auto-reactivate';
    protected $description = 'Reactivates deactivated items after set time';

    public function handle(): void
    {
        $now = Carbon::now('Asia/Kolkata')->format('H:i:s');


        // 1. Reactivate items if current time passed 'minutes'
        DB::table('item_master')
            ->where('item_status', 'D')
            ->whereNotNull('minutes')
            ->where('minutes', '<=', $now)
            ->update([
                'item_status' => 'A',
                'minutes' => null
            ]);

        DB::table('item_master')
        ->where('item_status', 'A')
        ->whereNotNull('minutes')
        ->where('minutes', '<=', $now)
        ->update([
            'item_status' => 'D',
            'minutes' => null
        ]);

        Log::info('✅ Item reactivated via cron at ' . now('Asia/Kolkata'));
    }
}

