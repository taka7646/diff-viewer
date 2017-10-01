<?php
namespace App\IO\Git;

class CommitInfo
{
    private $commitHash;
    private $metaData;
    private $comment;

    public function __construct()
    {
        $this->commitHash = '';
        $this->metaData = [];
        $this->comment = '';
    }

    public function getCommitDate(): DateTime
    {
        if(isset($this->metaData['Date'])){
            return new DateTime($this->metaData['Date']);
        }
        return null;
    }

    public function getMetaData($name)
    {
        return $this->metaData[$name]?? '';
    }

    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param array $lines
     */
    public static function parse(array $lines): array
    {
        $items = [];error_log(print_r($lines,true) );
        $len = count($lines);
        for ($i = 0; $i < $len; $i++) {
            if (strpos($lines[$i], 'commit ') !== 0) {
                continue;
            }
            $c = new self();
            $c->commitHash = substr($lines[$i], 7);
            $i++;
            // parse meta
            for (; $i < $len; $i++) {
                if (empty($lines[$i])) {
                    $i++;
                    break;
                }
                if (strpos($lines[$i], ':') === false) {
                    break;
                }
                list($k, $v) = explode(':', $lines[$i], 2);
                $c->metaData[$k] = trim($v);
            }
            // comment
            $comments = [];
            for (; $i < $len; $i++) {
                if (empty($lines[$i])) {
                    $i++;
                    break;
                }
                if (strpos($lines[$i], 'commit') === 0) {
                    $i--;
                    break;
                }
                $comments[] = ltrim($lines[$i]);
            }
            $c->comment = implode("\n", $comments);
            $items[] = $c;
        }
        return $items;
    }
}