<?php

namespace Differ\Differ\Formatters\Stylish;

use function Differ\Differ\DifStructure\getName;
use function Differ\Differ\DifStructure\getStat;
use function Differ\Differ\DifStructure\getValue;

const INDENT = '    ';

function stylish($difNotes): string
{
    return "{\n" . getStylish($difNotes) . "\n}";
}
function getStylish(array $difNotes, $indentNum = 1): string
{
    $res = [];
    foreach ($difNotes as $note) {
        $value = getValue($note);
        $res[] = parseDifNote($note, $indentNum);
        if (is_array($value)) {
            $res[] = array_key_exists('name', $value) ?
                parseDifNote($value, $indentNum) :
                getStylish($value, $indentNum + 1);
            $res[] = str_repeat(INDENT, $indentNum) . "}";
        }
    }
    return implode(PHP_EOL, $res);
}

function parseDifNote($difNote, int $indentNum = 0): string
{
    $name = getName($difNote);
    $stat = getStat($difNote);
    $value = getValue($difNote);

    $value = is_array($value) ? "{" : $value;
    $value = is_bool($value) ? ($value ? "true" : "false") : $value ?? "null";
    return substr(str_repeat(INDENT, $indentNum), 0, -2) .
        "{$stat} {$name}: " . $value;
}
