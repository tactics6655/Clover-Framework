<?php

namespace Clover\Classes;

class ArraySummarizer
{
    public function summarizeArray($arr)
    {
        $result = [];
        foreach ($arr as $item) {
            $matches = [];
            preg_match('/^(\D+)?(\d+)?(\D+)?(\d+)?$/', $item, $matches);
            $prefix = $matches[1] ?? '';
            $num1 = $matches[2] ?? '';
            $middle = $matches[3] ?? '';
            $num2 = $matches[4] ?? '';
            $key = $prefix . $middle;
            if (!isset($result[$key])) {
                $result[$key] = ['num1' => [], 'num2' => [],];
            }
            if ($num1 !== '') {
                $result[$key]['num1'][] = intval($num1);
            }
            if ($num2 !== '') {
                $result[$key]['num2'][] = intval($num2);
            }
        }

        $summary = [];
        foreach ($result as $key => $data) {
            $prefix = '';
            $middle = '';
            if (preg_match('/^(\D+)?(\d+)?$/', $key, $matches)) {
                $prefix = $matches[1] ?? '';
                $middle = $matches[2] ?? '';
            }

            $num1Str = $this->summarizeNumbers($data['num1']);
            $num2Str = $this->summarizeNumbers($data['num2']);

            $summaryText = '';

            if (!empty($prefix) || !empty($middle)) {
                $summaryText .= '[' . $prefix . $middle . ']';
            }

            $summaryText .= $num1Str;

            if (!empty($prefix)) {
                $summaryText .= ' [' . $prefix . ']';
            }

            if ($num1Str != $num2Str) {
                $summaryText .= $num2Str;
            }

            $summary[] = $summaryText;
        }

        return implode(' ', $summary);
    }
    private function summarizeNumbers($numbers)
    {
        if (empty($numbers)) {
            return '';
        }

        sort($numbers);

        $ranges = [];
        $start = $numbers[0];
        $prev = $start;
        $diff = null;

        for ($i = 1; $i < count($numbers); $i++) {
            $currentDiff = $numbers[$i] - $prev;
            if ($diff === null) {
                $diff = $currentDiff;
            }

            if ($currentDiff !== $diff) {
                if ($start === $prev) {
                    $ranges[] = $start;
                } elseif ($diff === 1) {
                    $ranges[] = $start . '-' . $prev;
                } else {
                    $ranges[] = sprintf("(%d + %dn ~ %d)", $diff, $start, $prev);
                }
                $start = $numbers[$i];
                $diff = null;
            }
            $prev = $numbers[$i];
        }

        if ($start === $prev) {
            $ranges[] = $start;
        } elseif ($diff === 1) {
            $ranges[] = $start . '-' . $prev;
        } else {
            $ranges[] = sprintf("(%d + %dn ~ %d)", $diff, $start, $prev);
        }
        return '[' . implode("\r\n", $ranges) . ']';
    }
}
