<?php

namespace Sunhill\Crawler\Handler;

use Illuminate\Support\Facades\DB;
use Sunhill\Crawler\Descriptor;

/**
 * Handles the entries in the database
 * @author klaus
 *
 */
class HandlerMime extends HandlerBase
{
  
    public static $prio = 6;
    
    /**
     * The signatures are taken from the getID3 project
     *
     * @var array
     */
    private static $header_infos = [
        'audio/flac' => '^fLaC',
        'audo/mpeg' => '^\\xFF[\\xE2-\\xE7\\xF2-\\xF7\\xFA-\\xFF][\\x00-\\x0B\\x10-\\x1B\\x20-\\x2B\\x30-\\x3B\\x40-\\x4B\\x50-\\x5B\\x60-\\x6B\\x70-\\x7B\\x80-\\x8B\\x90-\\x9B\\xA0-\\xAB\\xB0-\\xBB\\xC0-\\xCB\\xD0-\\xDB\\xE0-\\xEB\\xF0-\\xFB]',
        'image/jpeg' => '^\\xFF\\xD8\\xFF',
        'image/heic' => '\\x66\\x74\\x79\\x70\\x6d\\x69\\x66'
    ];
    
    function process(Descriptor $descriptor)
    {
        $this->verboseinfo("  Detecting mime");
        $descriptor->mime = $this->detectMime($descriptor->source);
        $descriptor->mimeID = $this->getMime($descriptor->mime);
        $descriptor->ext = $this->getExt($descriptor->source);
    }

    protected function getExt(string $file): String
    {
        $filebase = pathinfo($file,PATHINFO_BASENAME);
        if ($filebase[0] == '.') {
            return "";
        }
        return strtolower(pathinfo($file,PATHINFO_EXTENSION));
    }
    
    /**
     * @todo Add additonal detection here
     * @param string $source
     * @return String
     */
    protected function detectMime(string $source): String
    {
        $sample = $this->get_header($path);
        foreach (static::$header_infos as $mime => $pattern) {
            if (preg_match('#' . $pattern . '#s', $sample)) {
                return $mime;
            }
        }
        return $this->get_mime_type($path);
    }
    
    protected function getMime(string $mime): Int
    {
        $result = DB::table("mime")->where('mime',$mime)->first();
        if ($result) {
            return $result->id;
        }
        DB::table("mime")->insert(["mime"=>$mime]);
        $this->verboseinfo(" Mime added to database.");
        
        return DB::getPdo()->lastInsertId();
    }

    private function get_header(string $path)
    {
        $fp = fopen($path, 'r');
        $sample = fread($fp, 32774);
        fclose($fp);
        return $sample;
    }
    
    /**
     * Ermittelt den angepassten Mime-Typ für die übergebene Datei
     *
     * @param string $fullpath
     *            Pfad und Dateiname der zu untersuchenden Datei
     * @return string Mimestring der Datei
     */
    public function get_mime_type(string $fullpath)
    {
        $file_mime = mime_content_type($fullpath);
        switch ($file_mime) {
            case 'application/octet-stream':
            case 'text/plain':
                return $this->get_extended_mime_type($fullpath);
            case 'image/heif':
                return 'image/heic';
            case 'audio/x-flac':
                return 'audio/flac';
            default:
                return $file_mime;
        }
    }
    
    /**
     * Wenn die oben stehende Funktion keinen Mime-Typ herausfindet, müssen schwerere Geschütze aufgefahren werden
     *
     * @param string $fullpath
     */
    private function get_extended_mime_type(string $fullpath)
    {
        $ext = strtolower(pathinfo($fullpath, PATHINFO_EXTENSION));
        if (($ext == 'heic') || ($ext == 'heif')) {
            return 'image/heic';
        }
        
        // Jetzt bleibt uns nur noch die Endung
        if (isset(self::$ext_to_mime[$ext])) {
            return self::$ext_to_mime[$ext];
        } else {
            return 'application/octet-stream';
        }
    }
    
    /**
     * Ermittelt eine standarisierte Dateierweiterung der übergebenen Datei
     *
     * @param string $fullpath
     * @return string Dateierweiterung der Datei
     */
    public function get_ext(string $filename, string $mime_type)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (isset(self::$mime_to_ext[$mime_type])) {
            return self::$mime_to_ext[$mime_type];
        } else {
            return $ext;
        }
    }

    function matches(Descriptor $descriptor): Bool
    {
        return $descriptor->fileReadable() && !$descriptor->alreadyInDatabase();
    }
        
}
