<?php

namespace Differ\Differ;

use function Differ\Differ\Parsers\getDataFromFile;
use function Differ\Differ\Parsers\convertToString;
use function Differ\Differ\Parsers\stylish;
use function Differ\Differ\DifStructure\setDifNote;
use function Differ\Differ\DifStructure\sortDifNotes;

const STAT_NO_DIFF = ' ';
const STAT_DIF_IN_1 = '-';
const STAT_DIF_IN_2 = '+';

function genDiff(string $filePath1, string $filePath2)
{
    $data1 = getDataFromFile($filePath1);
    $data2 = getDataFromFile($filePath2);
    $result = getDifference($data1, $data2);

    return convertToString($result);
//    return $result;
//    return stylish($result);
}

function getDifference(array $first, array $second): array
{
    $keys1 = array_keys($first);
    $keys2 = array_keys($second);

    $inFirstOnly = array_diff($keys1, $keys2);
    $inSecondOnly = array_diff($keys2, $keys1);
    $inBoth = array_diff($keys1, $inFirstOnly);

    //get full sorted list of keys from both data arrays
    //in order not to sort result afterwards
    $allKeys = array_merge($inFirstOnly, $inSecondOnly, $inBoth);
    asort($allKeys);
    $allKeys = array_values($allKeys);
    // create dif structure by the kyes sorted prevously
    $difNotes = [];
    foreach ($allKeys as $key) {
        if (in_array($key, $inFirstOnly)) {
            $difNotes[] = setDifNote($key, $first[$key], STAT_DIF_IN_1);
        }
        if (in_array($key, $inSecondOnly)) {
            $difNotes[] = setDifNote($key, $second[$key], STAT_DIF_IN_2);
        }
        if (in_array($key, $inBoth)) {
            // array vs array
            if (is_array($first[$key]) && is_array($second[$key])) {
                $dif = getDifference($first[$key], $second[$key]);
                $difNotes[] = setDifNote($key, $dif, STAT_NO_DIFF, false);
            // array vs value or value vs array
            } elseif ($first[$key] === $second[$key]) {
                $difNotes[] = setDifNote($key, $first[$key], STAT_NO_DIFF);
            } else {
                $difNotes[] = setDifNote($key, $first[$key], STAT_DIF_IN_1);
                $difNotes[] = setDifNote($key, $second[$key], STAT_DIF_IN_2);
            }
        }
    }
    return $difNotes;
}
