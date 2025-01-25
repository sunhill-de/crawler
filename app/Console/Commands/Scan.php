<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sunhill\Crawler\ScanCrawler;

class Scan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =  'app:scan '.
        '{--recursive}'.
        '{--resume}'.
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
        
        $scanner = new ScanCrawler();
        $scanner->setRecursive($this->option('recursive'))
        ->setResume($this->option('resume'))
        ->setScanDir($this->argument('dir'))
        ->setNewLog($this->option('new-log'))
        ->setKnownLog($this->option('known-log'))
        ->setNewHandlers($handle_new)
        ->setKnownHandlers($handle_known)
        ->setCommand($this);
        $scanner->run();
    }
}
