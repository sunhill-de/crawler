<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Lib\crawler;

class Crawl extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'crawl {target=. : The file or directory to scan} {--keep} {--no-keep}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Crawls the given file or directory';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crawler = new crawler();
        $crawler->crawl($this,$this->argument("target"),$this->option("keep"),$this->getOutput()->getVerbosity());
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
