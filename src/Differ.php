<?php

namespace Differ\Differ;

use function Differ\Differ\Parsers\getDataFromFile;
use function Differ\Differ\Parsers\convertToString;

const STAT_NO_DIFF = " ";
const STAT_DIF_IN_1 = "-";
const STAT_DIF_IN_2 = "+";

function genDiff(string $filePath1, string $filePath2)
{
    $data1 = getDataFromFile($filePath1);
    $data2 = getDataFromFile($filePath2);
    $result = getDifference($data1, $data2);

    return convertToString($result);
}

function getDifference(array $data1, array $data2): array
{
    $data1Diff = array_diff($data1, $data2);
    $data2Diff = array_diff($data2, $data1);
    $noDiff = array_diff($data1, $data1Diff);

    $allKeys = array_keys(array_merge($data1, $data2));
    asort($allKeys, SORT_STRING);
    $allKeys = array_values($allKeys);

    $result = [];
    foreach ($allKeys as $key) {
        if (array_key_exists($key, $noDiff)) {
            $result[] = ['stat' => STAT_NO_DIFF, 'name' => $key, 'value' => $noDiff[$key]];
        }
        if (array_key_exists($key, $data1Diff)) {
            $result[] = ['stat' => STAT_DIF_IN_1, 'name' => $key, 'value' => $data1Diff[$key]];
        }
        if (array_key_exists($key, $data2Diff)) {
            $result[] = ['stat' => STAT_DIF_IN_2, 'name' => $key, 'value' => $data2Diff[$key]];
        }
    }
    return $result;
}
