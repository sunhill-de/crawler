<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Lib\Processors\Scanner;
use Illuminate\Support\Facades\DB;

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
        $this->scanDir(config('crawler.media_dir'));
    }

    private function scanDir(string $dirname,$depth=0)
    {
        $dir = dir($dirname);
        while (false !== ($entry = $dir->read())) {
            if (($entry == '.') || ($entry == '..')) {
                continue;
            }
            
            $filename = $dirname.'/'.$entry;
            
            if (is_dir($filename)){
                $this->handleDir($dirname."/".$filename);
            } else if (is_link($filename)) {
                $this->handleLink($dirname."/".$filename);
            } else if (is_file($filename)) {
                $this->handleFile($dirname."/".$filename);
            }
        }
        $dir->close();        
    }

    private function searchDir($path)
    {
        $result = DB::table('dirs')->where('full_path',$path)->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }
        
    private function handleDir($dirname)
    {
            
    }
    
    private function handleLink($linkname)
    {
        
    }
    
    private function handleFile($linkname)
    {
        
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
