<?php

namespace App\Console\Commands;

use App\Models\visit;
use Illuminate\Console\Command;

class VisitCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visit:autocheckout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $visit = visit::where('time_checkout', null);

        if ($visit->count() > 0) {
            foreach ($visit->get() as $row) {
                visit::where('visit_id', $row->visit_id)->update([
                    'alamat_checkout' => $row->alamat_checkin,
                    'photo_checkout' => $row->photo_checkin,
                    'latitude_checkout' => $row->latitude_checkin,
                    'longitude_checkout' => $row->longitude_checkin,
                    'accuracy_checkout' => $row->accuracy_checkin,
                    'time_checkout' => date('Y-m-d') . ' 23:59:59'
                ]);
            }
        }
    }
}
