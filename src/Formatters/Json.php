<?php

namespace Differ\Differ\Formatters\Json;

use function Differ\Differ\DifStructure\getName;
use function Differ\Differ\DifStructure\getStat;
use function Differ\Differ\DifStructure\getValue;

const INDENT = '    ';

function json($difNotes): string
{
    return json_encode(getJson($difNotes), JSON_PRETTY_PRINT);
}

function getJson(array $difNotes): array
{
    $res = [];
    $max = count($difNotes);
    for ($i = 0; $i < $max; $i++) {
        $stat = getStat($difNotes[$i]);
        $name = getName($difNotes[$i]);
        $value = getValue($difNotes[$i]);
        if ($stat == " ") {
            $res[$name] = is_array($value) ? getJson($value) : [$value, $value];
            continue;
        }
        if ($stat == "+") {
            $res[$name] = is_array($value) ? getJson($value) : ["", $value];
            continue;
        }
        if (!array_key_exists($i + 1, $difNotes)) {
            $res[$name] = is_array($value) ? getJson($value) : [$value, ""];
            continue;
        }
        $nameNext = getName($difNotes[$i + 1]);
        if ($name != $nameNext) {
            $res[$name] = is_array($value) ? getJson($value) : [$value, ""];
        } else {
            $valueNext = getValue($difNotes[$i + 1]);
            $res[$name] = is_array($value) ? getJson($value) : [$value, $valueNext];
            $i++;
        }
    }
    return $res;
}
