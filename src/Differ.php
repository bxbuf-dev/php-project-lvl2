<?php

namespace Differ\Differ;

function genDiff(string $filePath1, string $filePath2)
{
    $file1 = json_decode(file_get_contents($filePath1), true);
    $file2 = json_decode(file_get_contents($filePath2), true);

    $file1Diff = array_diff($file1, $file2);
    $file2Diff = array_diff($file2, $file1);
    $noDiff = array_diff($file1, $file1Diff);

    $allKeys = array_keys(array_merge($file1, $file2));
    asort($allKeys, SORT_STRING);
    $allKeys = array_values($allKeys);

    $result = "";
    foreach ($allKeys as $key) {
        if (array_key_exists($key, $noDiff)) {
            $result = $result . "  {$key}: " . json_encode($noDiff[$key]) . PHP_EOL;
        }
        if (array_key_exists($key, $file1Diff)) {
            $result = $result . "- {$key}: " . json_encode($file1Diff[$key]) . PHP_EOL;
        }
        if (array_key_exists($key, $file2Diff)) {
            $result = $result . "+ {$key}: " . json_encode($file2Diff[$key]) . PHP_EOL;
        }
    }
    return $result;
}
