<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class UpdateTableCommand extends Command
{
    protected $signature = 'update:table';  // This is your command name
    protected $description = 'Update specific table in database daily';

    public function handle()
    {
        // Example: update all rows in `orders` table where status is 'pending' to 'expired'
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


        $this->info('Table updated successfully.');
    }
}
