<?php

namespace App\Console\Commands;
use App\Http\Controllers\CronController;
use Illuminate\Console\Command;

class GetWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:getwinner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GetWinner';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "start cron\n";
        $getwinner = new CronController();
        $getwinner->pickWinner();
        echo "end cron\n";

    }
}
