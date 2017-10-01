<?php

namespace App\IO;

/**
 *
 */
class CommandWrapper {
    private $command;
    private $params;
    private $environments;
    private $currentDir;
    private $retCode;
    private $out;

    public function __construct(string $cmd) {
        $this->command = $cmd;
        $this->params = [];
        $this->environments = [];
        $this->currentDir = '';
    }

    public function setEnv(string $name, $value)
    {
        $this->environments[$name] = $value;
    }
    public function setEnvs(array $items)
    {
        $this->environments = array_merge($this->environments, $items);
    }

    public function setCurrentDir($path)
    {
        $this->currentDir = $path;
    }

    public function execute(array $params = []): bool
    {
        $params = array_merge($this->params, $params);
        $cmdLine = '';
        if (!empty($this->environments)) {
            $items = [];
            foreach($this->environments as $k => $v) {
                $items[] = "export {$k}='${$v}';";
            }
            $cmdLine .= implode('', $items);
        }
        if (!empty($this->currentDir)) {
            $cmdLine .= "cd {$this->currentDir};";
        }
        $params = array_map(function($v){
            return escapeshellarg($v);
        }, $params);
        $cmdLine .= "{$this->command} " . implode(' ', $params);
        error_log($cmdLine);
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
