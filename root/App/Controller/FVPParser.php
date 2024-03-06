<?php

namespace FVP;

use SplStack;
use SplDoublyLinkedList;

use Clover\Classes\File\Functions as FileFunctions;

class FVPParser {

    private $entry_point = [];

    private $stack;

    private $text;

    private array $split;

    private array $saying = [];

    public function __construct(string $text) {
        $this->text = $text;
        $this->split = explode("\n", $this->text);
    }

    private function getEntryPoint()
    {
        $entryPoint = null;

        $count = count($this->split);
        for ($i = 0; $i < $count; $i++) {
            $text = $this->split[$i];
            if (str_starts_with($text, 'ENTRYPOINT')) {
                preg_match("/^ENTRYPOINT \= ([a-z0-9_]++)/i", $text, $matched);

                $entryPoint = $matched[1];
            }
        }

        return $entryPoint;
    }

    private function getEntryPointIndex($entryPoint)
    {
        $startIndex = -1;
        $count = count($this->split);

        for ($i = 0; $i < $count; $i++) {
            $text = $this->split[$i];

            if ($text == $entryPoint.":") {
                $startIndex = $i + 1;
            }
        }

        return $startIndex;
    }

    private function getTextMatchedIndex($find)
    {
        return $this->entry_point[$find];
    }

    private function parseUntil(int $startIndex)
    {
        while ($startIndex > 0 && str_starts_with($this->split[$startIndex], "\t")) {
            $startIndex++;

            $text = $this->split[$startIndex];

            if (str_starts_with($text, "\tjmpcond")) {
                preg_match("/^\tjmpcond ([A-Z0-9_]++)/i", $text, $matched);
                
                $targetFunction = trim($matched[1]);

                $this->stack->push($startIndex);

                $startIndex = $this->getTextMatchedIndex($targetFunction.":");
                
                continue;
            } else if (str_starts_with($text, "\tjmp")) {
                preg_match("/^\tjmp ([A-Z0-9_]++)/i", $text, $matched);
                
                $targetFunction = trim($matched[1]);

                $startIndex = $this->getTextMatchedIndex($targetFunction.":");
                
                continue;
            } else if (str_starts_with($text, "\tpushstring")) {
                preg_match("/^\tpushstring (.*)/i", $text, $matched);
                $saying = trim($matched[1]);
                $saying = mb_convert_encoding($saying, mb_detect_encoding($saying), 'EUC-KR');

                FileFunctions::appendContent(__ROOT__."/scenario.txt", "\r\n".$saying);
            } else if (str_starts_with($text, "\tcall")) {
                preg_match("/^\tcall([a-zA-Z0-9 _]++)/i", $text, $matched);
                $callFunction = trim($matched[1]);

                $this->stack->push($startIndex);

                $startIndex = $this->getTextMatchedIndex($callFunction.":");

                continue;
            }
        }

        if (!$this->stack->isEmpty()) {
            $targetIndex = $this->stack->shift();
           
            $this->parseUntil($targetIndex);
        }
    }
    private function setEntyPoint()
    {
        $count = count($this->split);
        for ($i = 0; $i < $count; $i++) {
            $text = $this->split[$i];

            if (preg_match("/^([A-Za-z0-9_]{1,}):$/", $text, $matches)) {
                $this->entry_point[$matches[0]] = $i;
            }
        }
    }

    public function parse()
    {
        set_time_limit(-1);

        $this->stack = new SplStack();
        $this->stack->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP);

        $entryPoint = $this->getEntryPoint();
        $entryPoint = "_F5441_x4C_"; //_F5440_xFA_, _F5441_x4C_
        $startIndex = $this->getEntryPointIndex($entryPoint);

        $this->setEntyPoint();

        FileFunctions::write(__ROOT__."/scenario.txt", '');
        //FileFunctions::write(__ROOT__."/stack.txt", '');

        $this->parseUntil($startIndex);
    }

}