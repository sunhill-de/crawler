<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class delete-duplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-duplicates {--dup-file=./known.log} {--non-interactive}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected function handleLine(string $line, int $count)
    {
        list($original,$duplicate) = explode("=>", $line);
        $original = trim($original);
        $duplicate = trim($duplicate);
        $this->command->line("Original: $original, Size: ".filesize($original)." Hash: ".sha1_file($original));
        $this->command->line("Duplicate: $duplicate, Size: ".filesize($duplicate)." Hash: ".sha1_file($duplicate));
    }
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lines = file($this->option('dup-file'));
        foreach ($lines as $line) {
            $this->handleLine($line);
        }
    }
}
