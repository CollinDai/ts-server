<?php

namespace App\Console\Commands;

use App\Services\MovieDataService;
use Illuminate\Console\Command;

class Refresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movie:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh top ten movie';

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
        $this->comment('Start refreshing top ten');
        MovieDataService::getTopTen();
        $this->comment('Refreshing done!');
    }
}
