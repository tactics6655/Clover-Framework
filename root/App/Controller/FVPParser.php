<?php

namespace FVP;

class FVPParser {

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

        for ($i = 0; $i < count($this->split); $i++) {
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
        $startIndex = 0;
        for ($i = 0; $i < count($this->split); $i++) {
            $text = $this->split[$i];

            if ($text == $entryPoint.":") {
                $startIndex = $i + 1;
            }
        }

        return $startIndex;
    }

    private function getTextMatchedIndex($find)
    {
        $startIndex = -1;

        for ($i = 0; $i < count($this->split); $i++) {
            $text = $this->split[$i];

            if ($text == $find) {
                $startIndex = $i + 1;
            }
        }

        return $startIndex;
    }

    private function parseUntil(int $startIndex, ?string $prevMethod = null, int $stackIndex = 0)
    {
        if ($stackIndex > 0) {
            return;
        }

        while ($startIndex > 0 && str_starts_with($this->split[$startIndex], "\t")) {
            $startIndex++;

            $text = $this->split[$startIndex];

            if (str_starts_with($text, "\tjmpcond")) {
                preg_match("/^\tjmpcond ([A-Z0-9_]++)/i", $text, $matched);
                
                $targetFunction = trim($matched[1]);

                echo "[jmpcond] ".$prevMethod.">".$stackIndex."::".$targetFunction."<br/>";

                if ($prevMethod != $targetFunction) {
                    $callIndex = $this->getTextMatchedIndex($targetFunction.":");
                    $this->parseUntil($callIndex, $targetFunction, $stackIndex++);
                }

            } else if (str_starts_with($text, "\tjmp")) {
                preg_match("/^\tjmp ([A-Z0-9_]++)/i", $text, $matched);
                
                $targetFunction = trim($matched[1]);

                //echo "[jmp] ".$prevMethod.">".$stackIndex."::".$targetFunction."<br/>";

                $startIndex = $this->getTextMatchedIndex($targetFunction.":");
            }

            if (str_starts_with($text, "\tpushstring")) {
                preg_match("/^\tpushstring (.*)/i", $text, $matched);
                $saying = trim($matched[1]);
                $this->saying[] = mb_convert_encoding($saying, mb_detect_encoding($saying), 'EUC-KR');
            }

            if (str_starts_with($text, "\tcall")) {
                preg_match("/^\tcall([a-zA-Z0-9 _]++)/i", $text, $matched);
                $callFunction = trim($matched[1]);

                
                if ($prevMethod != $callFunction) {
                    $callIndex = $this->getTextMatchedIndex($callFunction.":");

                    echo "[call] ".$prevMethod.">".$stackIndex."::".$callFunction."<br/>";

                    $this->parseUntil($callIndex, $callFunction, $stackIndex++);
                }
            }
        }
    }

    public function parse()
    {
        //set_time_limit(60);
        $entryPoint = $this->getEntryPoint();
        $startIndex = $this->getEntryPointIndex($entryPoint);

        $this->parseUntil($startIndex);

        var_dump($this->saying);
    }

}