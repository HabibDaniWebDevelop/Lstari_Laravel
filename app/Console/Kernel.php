<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // php artisan schedule:list
        // php artisan schedule:run
        //  php artisan schedule:work

        $schedule->call(function () {
            DB::connection('messaging')->table('sossight')
            ->join(DB::connection('messaging')->raw('(SELECT id FROM sossight WHERE `Status` IN ("P", "Q") AND `DATE` <= DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH))) AS subq'), 'sossight.id', '=', 'subq.id')
            ->update([
                'Active' => '0',
                'Status' => 'C',
                'NoteReplay' => 'Batal By System Laravel',
            ]);
        })->weeklyOn(1, '11:07')->name('update massaging status')->description('meng gecek harian untuk data massaging yang tidak ada aktifitas lebih dari 1 bulan');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
