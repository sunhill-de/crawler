<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Lib\Processors\Scanner;

class Scan extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'scan {target=. : The file or directory to scan} {--keep} {--no-keep}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Scans the given file or directory';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $scanner = new Scanner();
        $scanner->scan($this,$this->argument("target"),$this->option("keep"),$this->getOutput()->getVerbosity());
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
