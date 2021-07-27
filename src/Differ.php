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

function getDifference(array $data1, array $data2): array
{
    $difNotes = [];
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);

    $key1Only = array_diff($keys1, $keys2);
    $key2Only = array_diff($keys2, $keys1);
    $keyBoth = array_diff($keys1, $key1Only);
    //get full sorted list of keys from both data arrays
    //in order not to sort result afterwards
    $allKeys = array_merge($key1Only, $key2Only, $keyBoth);
    asort($allKeys);
    $allKeys = array_values($allKeys);

    foreach ($allKeys as $key) {
        if (in_array($key, $key1Only)) {
            $difNotes[] = setDifNote($key, $data1[$key], STAT_DIF_IN_1);
        }
        if (in_array($key, $key2Only)) {
            $difNotes[] = setDifNote($key, $data2[$key], STAT_DIF_IN_2);
        }
        if (in_array($key, $keyBoth)) {
            // array vs array
            if (is_array($data1[$key]) && is_array($data2[$key])) {
                $difNotes[] = setDifNote(
                    $key,
                    getDifference($data1[$key], $data2[$key]),
                    STAT_NO_DIFF
                );
            // array vs value or value vs array
            } elseif ($data1[$key] == $data2[$key]) {
                $difNotes[] = setDifNote($key, $data1[$key], STAT_NO_DIFF);
            } else {
                $difNotes[] = setDifNote($key, $data1[$key], STAT_DIF_IN_1);
                $difNotes[] = setDifNote($key, $data2[$key], STAT_DIF_IN_2);
            }
        }
    }
    print_r($difNotes);
    return $difNotes;
}
