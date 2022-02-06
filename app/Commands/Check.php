<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Lib\Processors\Scanner;

class Check extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'check {--repair}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Checks the consistency of the media directory and the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->handleDir($this->getCheckDir());
    }

    private function handleDir(string $dirname)
    {
        $dir = dir($dirname);
        while (false !== ($entry = $dir->read())) {
            if (($entry == '.') || ($entry == '..')) {
                continue;
            }
            
            $filename = $dirname.'/'.$entry;
            
            if (is_dir($filename)){
                $this->handleDir($filename);
            } else if (is_file($filename)) {
                if (substr(pathinfo($filename,PATHINFO_FILENAME),0,5) == 'Check') {
                    $this->performChecks($filename);
                }
            }
        }
        $dir->close();        
    }

    private function performChecks($checkfile)
    {
        $this->info("Performing checks in $checkfile");
        require_once($checkfile);
        $classname = "\\Lib\\Checks\\".pathinfo($checkfile,PATHINFO_FILENAME);
        $check = new $classname($this);
        foreach (get_class_methods($check) as $method) {
            if (substr($method,0,5) == 'check') {
                $this->info("Performing check $method");
                $check->$method();
            }
        }
    }
    
    private function getCheckDir(): String
    {
        return dirname(__FILE__).'/../../lib/Checks';    
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
