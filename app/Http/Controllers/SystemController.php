<?php

namespace App/Http/Controllers;

class SystemController extends Controller
{
      public function css() {
        $content = view('templates.build',[
            'files'=>$this->get_files('css')
        ]);
        return response($content)->header('Content-Type','text/css');        
    }
    
    public function js() {
        $content = view('templates.build',[
            'files'=>$this->get_files('js')
        ]);
        return response($content)->header('Content-Type','text/javascript');
    }
    
    protected function get_files(string $dir) {
        $result = [];
        $basedir = base_path('/resources/'.$dir);
        $files = [];
        $d = dir($basedir);
        while (false !== ($entry = $d->read())) {
            if (is_file($basedir.'/'.$entry)) {
                $files[] = $basedir.'/'.$entry;
            }
        }
        $d->close(); 
        sort($files);
        foreach ($files as $file) {
            $result[] = file_get_contents($file);
        }
        return $result;
    }
}
