<?php

class LocalStorage
 {
    private $file = null;
    private $directory = null;
    private $destroytime =  720;
    
    public function __construct($locale = null,$timezone = null, $filename = null)
    {
        // set directory
        $this->directory = dirname(__FILE__) . '/storage/';

        // set local time zone
        $timezone = $timezone ? $timezone : 'Europe/Istanbul';
        // set locale
        $locale = $locale ? $locale : 'tr_TR.UTF-8';

        // set file name
        $filename = $filename ? $filename : 'localstorage.json';
        // set file path
        $filepath = $this->directory . DIRECTORY_SEPARATOR . $filename;
        // set file
        $this->file = $filepath;
        // set local time zone
        date_default_timezone_set($timezone);
        // set locale
        setlocale(LC_ALL, $locale);

        if(!file_exists($this->file)){
            // Create Directory
            if(!is_dir($this->directory)){
                mkdir($this->directory, 0777, true);
            }
            touch($this->file);
        }

        
    }

    public function set($value)
    {
        $key =  base64_encode(serialize($value));
        $data = json_decode(file_get_contents($this->file), true);
        $data[$key] = $value;
        file_put_contents($this->file, json_encode($data));

        return $this;
    }

    public function get($key)
    {
        $data = json_decode(file_get_contents($this->file), true);
        $key = base64_encode(serialize($key));
        return isset($data[$key]) ? $data[$key] : false;
    }

    public function delete($key)
    {
        $data = json_decode(file_get_contents($this->file), true);
        $key = base64_encode(serialize($key));
        unset($data[$key]);
        file_put_contents($this->file, json_encode($data));

        return $this;
    }

    public function clear()
    {
        // delete directory and file
        if(is_dir($this->directory)){
            // delete files
            $files = glob($this->directory . '/*');
            foreach($files as $file){
                if(is_file($file)){
                    unlink($file);
                }
            }
            // delete directory
            rmdir($this->directory);
        }
        return $this;

    }

    public function all ()
    {
        $data = json_decode(file_get_contents($this->file), true);
        return $data;
    }

    public function getalljson()
    {
        // check if file exists
        if(file_exists($this->file)){                
            // return file content
            $data = json_decode(file_get_contents($this->file), true);
            $count = count($data);
            $new   = [];
            $new['FileName'] = basename($this->file);
            $new['FileDir']  = dirname($this->file);
            $new['Create']   = date('d.m.Y H:i:s', filectime($this->directory));
            $new['Update']   = date('d.m.Y H:i:s',filemtime($this->file));
            $new['DestroyTime']  = $this->destroytime;
            $new['CountItem']    = $count;    
            $new['Result']   = $data;
            $data = json_encode($new);
            return $data;
        }else{
            return false;
        }
    }

    public function checkfiletime()
    {
        $directorytime = filemtime($this->directory); 
        $now = time();
        $diff = $now - $directorytime;
        $minute = floor($diff / 60);
        
        // is file older than destroy time
        if($minute >= $this->destroytime){
            $this->clear();
            return true;
        }
        
        return false;
    }

    public function __destruct()
    {
        // check file time
        $this->checkfiletime();


    }

 }
 

?>