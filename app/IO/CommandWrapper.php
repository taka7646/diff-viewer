<?php

namespace App\IO;

/**
 *
 */
class CommandWrapper {
    private $command;
    private $params;
    private $currentDir;
    private $retCode;
    private $out;

    public function __construct(string $cmd) {
        $this->command = $cmd;
        $this->params = [];
        $this->currentDir = '';
    }

    public function execute(array $params = []): bool
    {
        $params = array_merge($this->params, $params);
        $cmdLine = "{$this->command} " . implode(' ', $params);
        exec($cmdLine, $out, $retCode);
        $this->out = $out;
        $this->retCode = $retCode;
        return $retCode == 0;
    }

    public function getOutput(): array
    {
        return $this->out;
    }
}
