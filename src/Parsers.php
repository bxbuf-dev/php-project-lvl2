<?php

namespace Differ\Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Differ\Differ\DifStructure\getStat;
use function Differ\Differ\DifStructure\getName;
use function Differ\Differ\DifStructure\getValue;

function getDataFromFile(string $filePath): array
{
    $fileType = strstr($filePath, '.');
    switch ($fileType) {
        case ".json":
            return json_decode(file_get_contents($filePath), true);
        case ".yml" || "yaml":
            return Yaml::parse(file_get_contents($filePath));
    }
}

function convertToString(array $difNotes): string
{
    $result = [];
    foreach ($difNotes as $note) {
        $name = getName($note);
        $stat = getStat($note);
        $value = getValue($note);
        $result[] = "{$stat} {$name}: " . json_encode($value, JSON_PRETTY_PRINT);
    }
    return str_replace('"', '', implode(PHP_EOL, $result) . PHP_EOL);
}

function stylish(array $data): string
{
    $result = [];
    foreach ($data as $key) {
        $result[] = "{$key['stat']} {$key['name']}: " . json_encode($key['value']);
    }
    return str_replace('"', '', json_encode($result, JSON_PRETTY_PRINT));
}
