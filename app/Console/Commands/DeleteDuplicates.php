<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-duplicates {--dup-file=./known.log} {--non-interactive} {--no-hash}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    protected function handleLine(string $line)
    {
        list($original,$duplicate) = explode("=>", $line);
        $original = trim($original);
        $duplicate = trim($duplicate);
        if ($this->option('no-hash')) {
            $original_hash = 'no hash';
            $duplicate_hash = 'no hash';
        } else {
            $original_hash = sha1_file($original);
            $duplicate_hash = sha1_file($duplicate);            
        }
        if (file_exists($original) && file_exists($duplicate)) {
            $this->line("Size: ".filesize($original)." Hash:  $original_hash Original: $original");
            $this->line("Size: ".filesize($duplicate)." Hash: $duplicate_hash Duplicate: $duplicate");
            if ($this->option('non-interactive')) {
                $answer = 'y';
            } else {
                $answer = $this->ask('Delete (D(uplicate)/O(orininal)/N(othing)','D');
            }
            switch (strtolower($answer)) {
                case 'd':
                    $this->line("Deleting duplicate");
                    unlink($duplicate);
                    break;
                case 'o':
                    $this->line("Deleting original");
                    unlink($original);
                    break;
                default:
                    $this->line("Do nothing");
            }
        }
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
