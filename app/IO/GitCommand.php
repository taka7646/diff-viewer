<?php

namespace App\IO;

class GitCommand extends CommandWrapper
{
    public function __construct() {
        parent::__construct("git");
    }

    public function log($path)
    {
        return $this->execute(['log', '--', $path]);
    }
}