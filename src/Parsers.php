<?php

namespace Differ\Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Differ\Differ\DifStructure\getStat;
use function Differ\Differ\DifStructure\getName;
use function Differ\Differ\DifStructure\getValue;

const INDENT = '  ';

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

function stylish(array $difNotes): string
{
    $result = array_map(fn ($note) => parseDifNote($note), $difNotes);
    $result = addIndent($result);
    return implode(PHP_EOL, $result) . PHP_EOL;
}

function parseDifNote($difNote): string
{
    $name = getName($difNote);
    $stat = getStat($difNote);
    $value = getValue($difNote);
    return str_replace('"', '', "{$stat} {$name}: " . json_encode($value, JSON_PRETTY_PRINT));
}

function addIndent(array $difNotes): array
{
    $res = array_map(fn ($note) => is_array($note) ? addIndent($note) : INDENT . $note, $difNotes);
    return array_merge(['{'], $res, ['}']);
}
