<?php

namespace Differ\Differ\InOut;

use Symfony\Component\Yaml\Yaml;

function getDataFromFile(string $filePath): array
{
    $fileType = strstr($filePath, '.');
    switch ($fileType) { 
        case ".json":
            return json_decode(file_get_contents($filePath), true);
        case ".yml":
            return Yaml::parse(file_get_contents($filePath));
    }
}

function convertToString(array $data): string
{
    $result = [];
    foreach ($data as $key) {
        $result[] = "{$key['stat']} {$key['name']}: " . json_encode($key['value']);
    }
    return implode(PHP_EOL, $result) . PHP_EOL;
}
