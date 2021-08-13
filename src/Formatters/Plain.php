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
        if ($stat == " ") {
            if (is_array($value)) {
                $name = $printName . $name . ".";
                $res[] = getPlain($value, $name);
            }
            continue;
        }
        if ($stat == "+") {
            $name = $printName . $name;
            $res[] = "Property '{$name}' was added with value: {$printValue}";
            continue;
        }
        // stat = '-'
        if (!array_key_exists($i + 1, $difNotes)) {
        //    $name = $printName . $name;
            $res[] = "Property '{$name}' was removed";
            continue;
        }
        $nameNext = getName($difNotes[$i + 1]);
        if ($name != $nameNext) {
            $name = $printName . $name;
            $res[] = "Property '{$name}' was removed";
        } else {
            $name = $printName . $name;
            $valueNext = getValue($difNotes[$i + 1]);
            $printValueNext = formatValue($valueNext);
            $res[] = "Property '{$name}' was updated. From {$printValue} to {$printValueNext}";
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
