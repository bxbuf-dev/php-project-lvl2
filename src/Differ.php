<?php

namespace Differ\Differ;

function genDiff(string $filePath1, string $filePath2)
{
    $data1 = getDataFromFile($filePath1, true);
    $data2 = getDataFromFile($filePath2, true);

    $data1Diff = array_diff($data1, $data2);
    $data2Diff = array_diff($data2, $data1);
    $noDiff = array_diff($data1, $data1Diff);

    $allKeys = array_keys(array_merge($data1, $data2));
    asort($allKeys, SORT_STRING);
    $allKeys = array_values($allKeys);

    $result = [];
    foreach ($allKeys as $key) {
        if (array_key_exists($key, $noDiff)) {
            $result[] = "  {$key}: " . json_encode($noDiff[$key]);
        }
        if (array_key_exists($key, $data1Diff)) {
            $result[] = "- {$key}: " . json_encode($data1Diff[$key]);
        }
        if (array_key_exists($key, $data2Diff)) {
            $result[] = "+ {$key}: " . json_encode($data2Diff[$key]);
        }
    }
    return implode(PHP_EOL, $result) . PHP_EOL;
}

function getDataFromFile(string $filePath, bool $isJson): array
{
    return json_decode(file_get_contents($filePath), $isJson);
}
