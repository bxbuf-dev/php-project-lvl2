<?php

namespace Differ\Differ;

function genDiff(string $filePath1, string $filePath2)
{
    $data1 = getDataFromFile($filePath1, true);
    $data2 = getDataFromFile($filePath2, true);
    $result = getDifference($data1, $data2);
    return convertToString($result);
}

function getDataFromFile(string $filePath, bool $isJson): array
{
    return json_decode(file_get_contents($filePath), $isJson);
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
            $result[] = [
                'stat' => " ",
                'name' => $key,
                'value' => $noDiff[$key]
            ];
        }
        if (array_key_exists($key, $data1Diff)) {
            $result[] = [
                'stat' => "-",
                'name' => $key,
                'value' => $data1Diff[$key]
            ];
        }
        if (array_key_exists($key, $data2Diff)) {
            $result[] = [
                'stat' => "+",
                'name' => $key,
                'value' => $data2Diff[$key]
            ];
        }
    }

    return $result;
}

function convertToString(array $data): string
{
    $result = [];
    foreach ($data as $key) {
        $result[] = "{$key['stat']} {$key['name']}: " . json_encode($key['value']);
    }
    return implode(PHP_EOL, $result) . PHP_EOL;
}
