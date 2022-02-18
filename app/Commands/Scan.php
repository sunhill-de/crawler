<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Sunhill\Crawler\Processors\Scanner;
use LaravelZero\Framework\Commands\Command;
use App\Commands\CrawlerCommand;

class Scan extends Command
{

    use CrawlerCommand;
    
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'scan {target} {--s|sync} {--S|no-sync} {--r|recursive} {--R|no-recursive} {--K|skip-duplicates} {--k|no-skip-duplicates} {--P|suppress-source} {--p|no-suppress-source} {--e|erase-empty-dirs} {--tags=} {--associations=}';

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
        $scanner->scan($this,
            $this->argument("target"),
            $this->getSync(),
            $this->getRecursive(),
            $this->getSkipDuplicates(),
            $this->getSuppressSource(),
            $this->getOutput()->getVerbosity(),
            $this->getOption('erase-empty-dirs'),           
            $this->getTags(),
            $this->getAssociations());
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
