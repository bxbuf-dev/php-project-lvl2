<?php

namespace Differ\Differ\Formatters\Plain;

use function Differ\Differ\DifStructure\getName;
use function Differ\Differ\DifStructure\getStat;
use function Differ\Differ\DifStructure\getValue;

function plain(array $difNotes): string
{
    $res = flatten(getPlain($difNotes));
    return implode(PHP_EOL, $res);
}

function getPlain($difNotes, string $printName = ""): array
{
    $res = [];
    $max = count($difNotes);
    for ($i = 0; $i < $max; $i++) {
        $stat = getStat($difNotes[$i]);
        $name = getName($difNotes[$i]);
        $value = getValue($difNotes[$i]);
        $printValue = formatValue($value);
        $fullName = $printName . $name;
        if ($stat == " ") {
            if (is_array($value)) {
                $fullName = $fullName . ".";
                $res[] = getPlain($value, $fullName);
            }
            continue;
        }
        if ($stat == "+") {
            $res[] = "Property '{$fullName}' was added with value: {$printValue}";
            continue;
        }
        // stat = '-'
        if (!array_key_exists($i + 1, $difNotes)) {
            $res[] = "Property '{$fullName}' was removed";
            continue;
        }
        $nameNext = getName($difNotes[$i + 1]);
        if ($name != $nameNext) {
            $res[] = "Property '{$fullName}' was removed";
        } else {
            $valueNext = getValue($difNotes[$i + 1]);
            $printValueNext = formatValue($valueNext);
            $res[] = "Property '{$fullName}' was updated. From {$printValue} to {$printValueNext}";
            $i++;
        }
    }
    return $res;
}

function formatValue($value): string
{
    if (is_string($value)) {
        return "'" . $value . "'";
    }
    if (is_array($value)) {
        return "[complex value]";
    }
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }
    return strval($value ?? "null");
}

function flatten(array $array): array
{
    $return = [];
    foreach ($array as $value) {
        if (is_array($value)) {
            $return = [...$return, ...flatten($value)];
        } else {
            $return[] = $value;
        }
    }
    return $return;
}
