<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\DB;

class Scan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =  'app:scan '.
                            '{--recursive}'.
                            '{--handle-new=record}'.
                            '{--handle-known=ignore}'. 
                            '{--new-log=./new.log}'. 
                            '{--known-log=./known.log}'.
                            '{dir=.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected function check_new_handlers(array $new_handlers)
    {
        $allowed_new_handlers = ['ignore','log','record'];
        foreach ($new_handlers as $handler) {
            if (!in_array($handler, $allowed_new_handlers)) {
                throw new \Exception("'$handler' is not allowed here");
            }
        }
    }
    
    protected function check_known_handlers(array $known_handlers)
    {
        $allowed_known_handlers = ['ignore','log','delete','record'];
        foreach ($known_handlers as $handler) {
            if (!in_array($handler, $allowed_known_handlers)) {
                throw new \Exception("'$handler' is not allowed here");
            }
        }        
    }
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $handle_new = explode(',', $this->option('handle-new'));
        $this->check_new_handlers($handle_new);
        
        $handle_known = explode(',', $this->option('handle-known'));
        $this->check_known_handlers($handle_known);
        
        $new_log = $this->option('new-log');
        $known_log = $this->option('known-log');
        
        $this->scan($handle_new, $handle_known, $new_log, $known_log, $this->option('recursive'), $this->argument('dir'));
    }

    protected function handle_known_file(string $file, array $known, $known_log, string $hash, $query)
    {
        $this->line("Found known file '$file'=>'".$query->path."'", null, 'v');
        foreach ($known as $handler) {
            switch ($handler) {
                case 'log':
                    $this->line("Log file '$file", null, 'vv');
                    fwrite($known_log.'=>'.$query->path, $file."\n");
                    break;
                case 'record':
                    $this->line("Record file '$file", null, 'vv');
                    DB::table('duplicate_files')->insert(
                        ['hash'=>$hash,'path'=>$file]);
                    break;
                case 'delete':
                    $this->line("Deleting file '$file", null, 'v');
                    break;
            }
        }
    }
    
    protected function handle_new_file(string $file, array $new, $new_log, string $hash)
    {
        $this->line("Found new file '$file", null, 'v');        
        foreach ($new as $handler) {
            switch ($handler) {
                case 'log':
                    $this->line("Log file '$file", null, 'v');
                    fwrite($new_log, $file."\n");
                    break;
                case 'record':
                    $this->line("Record file '$file", null, 'v');
                    DB::table('found_files')->insert(
                        ['hash'=>$hash,'path'=>$file,'mime'=>mime_content_type($file)]);
                    break;
            }
        }
    }
    
    protected function scan_dir(array $new, array $known, $new_log, $known_log, bool $recursive, string $dir)
    {
        $handler = dir($dir);
        
        $this->line("Entering '$dir'", null, 'v');
        if ($this->option('verbose')) {
            
        }
        while (false !== ($entry = $handler->read())) {
            if (($entry == '.') || ($entry == '..')) {
                continue;
            }
            if (is_dir($dir.'/'.$entry)) {
                if ($recursive) {
                    $this->scan_dir($new,$known,$new_log,$known_log,$recursive,$dir.'/'.$entry);
                }
            } else if (is_link($dir.'/'.$entry)) {
                
            } else {
                $hash = sha1_file($dir.'/'.$entry);
                if ($query = DB::table('found_files')->where('hash',$hash)->first()) {
                    $this->handle_known_file($dir.'/'.$entry, $known, $known_log, $hash, $query);
                } else {
                    $this->handle_new_file($dir.'/'.$entry, $new, $new_log, $hash);
                }
            }
        }
        $this->line("Leaving '$dir'", null, 'v');
        
        $handler->close();
    }
    
    protected function scan(array $new, array $known, string $new_log, string $known_log, bool $recursive, string $dir)
    {
        $new_log_file = false;
        if (in_array('log', $new)) {
            $new_log_file = fopen($new_log, "w");
        }
        
        $known_log_file = false;
        if (in_array('log', $known)) {
            $known_log_file = fopen($known_log, "w");
        }

        $this->scan_dir($new, $known, $new_log_file, $known_log_file, $recursive, $dir);
        
        if (in_array('log', $new)) {
            fclose($new_log_file);
        }
        if (in_array('log', $known)) {
            fclose($known_log_file);
        }
    }
    
    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
