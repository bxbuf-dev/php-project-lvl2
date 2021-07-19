<?php

namespace Differ\Differ;

function genDiff(string $filePath1, string $filePath2)
{
    $file1 = file_get_contents($filePath1);
    $file2 = file_get_contents($filePath2);
    $fileData1 = json_decode($file1, true);
    $fileData2 = json_decode($file2, true);

    $existInFirst = array_diff($fileData1, $fileData2);
    $existInSecond = array_diff($fileData2, $fileData1);
    $sameInBoth = array_diff($fileData1, $existInFirst);

    $allKeys = array_keys(array_merge($fileData1, $fileData2));
    asort($allKeys, SORT_STRING);
    $allKeys = array_values($allKeys);

    $result = "";
    foreach ($allKeys as $key) {
        if (array_key_exists($key, $sameInBoth)) {
            $result = $result . "  {$key}: " . json_encode($sameInBoth[$key]) . PHP_EOL;
        }
        if (array_key_exists($key, $existInFirst)) {
            $result = $result . "- {$key}: " . json_encode($existInFirst[$key]) . PHP_EOL;
        }
        if (array_key_exists($key, $existInSecond)) {
            $result = $result . "+ {$key}: " . json_encode($existInSecond[$key]) . PHP_EOL;
        }
    }
    return $result;
}
