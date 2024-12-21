<?php

namespace App\Console;

use App\Http\Controllers\AuctionController;
use App\Models\Auction;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            $now = now();
            $interval = $now->copy()->addMinutes(30);

            // Buscamos leilões 'Active' que terminam EXACTAMENTE daqui a 30 minutos
            // ou que estejam no range que quiser
            $auctionsEndingSoon = Auction::where('status', 'Active')
                ->whereBetween('ending_date', [$now, $interval])
                ->get();

            foreach ($auctionsEndingSoon as $auction) {
                app(AuctionController::class)->notifyAuctionEnding($auction);
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
