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
        $resValue = is_array($value) ? getJson($value) : $value;
        if ($stat == " ") {
            $res[$name] = [$resValue, $resValue];
            continue;
        }
        if ($stat == "+") {
            $res[$name] = ["", $resValue];
            continue;
        }
        if (!array_key_exists($i + 1, $difNotes)) {
            $res[$name] = [$resValue, ""];
            continue;
        }
        $nameNext = getName($difNotes[$i + 1]);
        if ($name != $nameNext) {
            $res[$name] = [$resValue, ""];
        } else {
            $valueNext = getValue($difNotes[$i + 1]);
            $valueNext = is_array($valueNext) ? getJson($valueNext) : $valueNext;
            $res[$name] = [$resValue, $valueNext];
            $i++;
        }
    }
    return $res;
}
