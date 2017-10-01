<?php
namespace App\IO\Git;

class File
{
    private $isDir;
    private $name;
    private $fullName;
    private $childs;

    private static $empty;

    public function __construct($name, $fullName, $isDir)
    {
        $this->name = $name;
        $this->fullName = $fullName;
        $this->isDir = $isDir;
        $this->childs = [];
    }

    public function isDirectory()
    {
        return $this->isDir;
    }

    public function get($name): File
    {
        return $this->childs[$name];
    }

    public function getOrCreate($name, $fullName): File
    {
        if(isset($this->childs[$name])){
            return $this->childs[$name];
        }
        $d = new self($name, $fullName, true);
        $this->childs[$name] = $d;
        return $d;
    }

    public function appendChild($name, $fullName, $isDir)
    {
        $f = new self($name, $fullName, $isDir);
        $this->childs[$name] = $f;
        return $f;
    }
    
    public static function parse(array $lines): File
    {
        $root = new self('', '', true);
        foreach($lines as $line) {
            $parts = explode('/', $line);
            $d = $root;
            for($i = 0, $len = count($parts); $i < $len; $i++) {
                if($i == $len-1) {
                    $d->appendChild($parts[$i], $line, false);
                    break;
                }
                $d = $d->getOrCreate($parts[$i], $line);
            }
        }

        return $root;
    }

    public static function getEmpty(){
        if(self::$empty == null){
            self::$empty = new self('','',true);
        }
        return self::$empty;
    }
}