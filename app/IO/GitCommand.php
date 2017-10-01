<?php

namespace App\IO;

use App\IO\Git\CommitInfo;
use App\IO\Git\File;

class GitCommand extends CommandWrapper
{
    public function __construct($basePath) {
        parent::__construct("git");
        $this->setCurrentDir($basePath);
    }

    public function log($path, $count = 100): array
    {
        $success = $this->execute(['--no-pager', 'log', '-n', $count, '--', $path]);
        $items = [];
        if($success){
            $items = CommitInfo::parse($this->getOutput());
        }
        return $items;
    }

    public function ls(): File
    {
        $success = $this->execute(['ls-files']);
        if($success){
            $items = File::parse($this->getOutput());
            var_dump($items);
            return $items;
        }
        return File::getEmpty();
    }
}