<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LinkDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:link-duplicates {--dup-file=./known.log} {--dest-dir=.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected function handleLine(string $line, int $count)
    {
        $parts = explode("=>", $line);
        symlink(trim($parts[1]),$this->option('dest-dir')."/$count-original");
        symlink(trim($parts[0]),$this->option('dest-dir')."/$count-duplicate");        
    }
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = 1;
        $lines = file($this->option('dup-file'));
        foreach ($lines as $line) {
            $this->handleLine($line,$count++);
        }
    }
}
