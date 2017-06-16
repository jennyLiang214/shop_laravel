<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReminderEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $cargo;

    /**
     * SendReminderEmail constructor.
     * @param $cargo
     *
     */
    public function __construct($cargo)
    {
        $this->cargo = $cargo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('send remind email to' . $this->cargo->cargo_name);
    }
}
