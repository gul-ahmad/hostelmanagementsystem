<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Notifications\UserReservationStarting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendDueReservationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hms:send-reservations-notifications';

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

        Reservation::query()
            ->with('user')
            ->where('status', Reservation::STATUS_ACTIVE)
            ->where('start_date', now()->toDateString())
            ->each(function ($reservation) {

                Notification::send($reservation->user, new UserReservationStarting($reservation));
            });


        return Command::SUCCESS;
    }
}
