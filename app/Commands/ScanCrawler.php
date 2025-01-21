<?php

use phpDocumentor\Reflection\Types\Static_;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\DB;

class ScanCrawler
{
    
    protected $recursive = false;
    
    public function setRecursive(bool $value = true): static
    {
        $this->recursive = $value;
        return $this;
    }
    
    protected $scan_dir = '.';
    
    public function setScanDir(string $scan_dir): static
    {
        $this->scan_dir = $scan_dir;
        return $this;
    }
    
    protected $new_log = 'new.log';
    
    public function setNewLog(string $new_log): static
    {
        $this->new_log = $new_log;
        return $this;
    }
    
    protected $known_log = 'new.log';
    
    public function setKnownLog(string $known_log): static
    {
        $this->known_log = $known_log;
        return $this;
    }
    
    protected $new_handlers = ['record'];
    
    public function setNewHandlers(array $new_handlers): static
    {
        $this->new_handlers = $new_handlers;
        return $this;
    }
    
    protected $known_handlers = ['log'];
    
    public function setKnownHandlers(array $known_handlers): static
    {
        $this->known_handlers = $known_handlers;
        return $this;
    }
    
    protected $command;
    
    public function setCommand(Command $command): static
    {
        $this->command = $command;
        return $this;
    }
        
    protected function handle_known_file(string $file, $query)
    {
        $this->command->line("Found known file '$file'=>'".$query->path."'", null, 'v');
        foreach ($this->known_handlers as $handler) {
            switch ($handler) {
                case 'log':
                    $this->command->line("Log file '$file", null, 'vv');
                    fwrite($this->known_log.'=>'.$query->path, $file."\n");
                    break;
                case 'record':
                    $this->command->line("Record file '$file", null, 'vv');
                    DB::table('duplicate_files')->insert(
                        ['hash'=>$query-hash,'path'=>$file]);
                    break;
                case 'delete':
                    $this->command->line("Deleting file '$file", null, 'v');
                    break;
            }
        }
    }
    
    protected function handle_new_file(string $file, string $hash)
    {
        $this->command->line("Found new file '$file", null, 'v');
        foreach ($this->new_handlers as $handler) {
            switch ($handler) {
                case 'log':
                    $this->command->line("Log file '$file", null, 'v');
                    fwrite($this->new_log, $file."\n");
                    break;
                case 'record':
                    $this->command->line("Record file '$file", null, 'v');
                    DB::table('found_files')->insert(
                        ['hash'=>$hash,'path'=>$file,'mime'=>mime_content_type($file)]);
                    break;
            }
        }
    }
    
    protected function scan_dir(string $dir)
    {
        $handler = dir($dir);
        
        $this->command->line("Entering '$dir'", null, 'v');
        while (false !== ($entry = $handler->read())) {
            if (($entry == '.') || ($entry == '..')) {
                continue;
            }
            if (is_dir($dir.'/'.$entry)) {
                if ($this->recursive) {
                    $this->scan_dir($dir.'/'.$entry);
                }
            } else if (is_link($dir.'/'.$entry)) {
                
            } else {
                $hash = sha1_file($dir.'/'.$entry);
                if ($query = DB::table('found_files')->where('hash',$hash)->first()) {
                    $this->handle_known_file($dir.'/'.$entry, $query);
                } else {
                    $this->handle_new_file($dir.'/'.$entry, $hash);
                }
            }
        }
        $this->command->line("Leaving '$dir'", null, 'v');
        
        $handler->close();
    }

    protected $new_log_file = false;
    protected $known_log_file = false;
    
    public function run()
    {
        if (in_array('log', $this->new_handlers)) {
            $this->new_log_file = fopen($this->new_log, "w");
        }
        
        if (in_array('log', $this->known_handlers)) {
            $this->known_log_file = fopen($this->known_log, "w");
        }
        
        $this->scan_dir(realpath($this->scan_dir));

        if ($this->new_log_file) {
            fclose($this->new_log_file);
        }
        if ($this->known_log_file) {
            fclose($this->known_log_file);
        }
    }
    
    
}