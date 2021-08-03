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

function stylish($difNotes)
{
    print_r("\nCurrent loop =>\n");
    print_r("{\n" . getStylish($difNotes) . "\n}\n");
    return "{\n" . getStylish($difNotes) . "\n}\n";
}
function getStylish(array $difNotes, $indentNum = 1): string
{
    $res = [];
    foreach ($difNotes as $note) {
        $stat = getStat($note);
        $name = getName($note);
        $value = getValue($note);
        if (!is_array($value)) {
            $res[] = parseDifNote($note, $indentNum);
        } else {
            $res[] = str_repeat(INDENT, $indentNum) . "{$stat} {$name}: {";
            $res[] = array_key_exists('name', $value) ?
                parseDifNote($value, $indentNum) :
                getStylish($value, $indentNum + 1);
            $res[] = str_repeat(INDENT, $indentNum + 1) . "}";
        }
    }
    return implode(PHP_EOL, $res);
}

function parseDifNote($difNote, int $indentNum = 0): string
{
    $name = getName($difNote);
    $stat = getStat($difNote);
    $value = getValue($difNote);
    $value = is_bool($value) ? ($value ? "true" : "false") : $value;
    return str_repeat("  ", $indentNum) . "{$stat} {$name}: " . $value;
}
