<?php

namespace Differ\Differ\DifStructure;

function setDifNote(string $key, $value, string $stat, $parsValue = true): array
{
    $dif = [
        'name' => $key,
        'value' => is_array($value) && $parsValue ?
            array_map(
                fn ($k, $v) => setDifNote($k, $v, " "),
                array_keys($value),
                array_values($value)
            ) : $value,
        'stat' => $stat
    ];
    return $dif;
}

function getStat($dif)
{
    return $dif['stat'];
}

function getName($dif)
{
    return $dif['name'];
}

function getValue($dif)
{
    return $dif['value'];
}

function sortDifNotes($difNotes): array
{
    //sort by 'name' key
    $name = array_column($difNotes, 'name');
    array_multisort($difNotes, $name, SORT_DESC);
    // sort same names by by 'stat' key
    usort($difNotes, function ($a, $b) {
        if ($a['name'] != $b['name']) {
            return 0;
        }
        return $a['stat'] == '-' ? -1 : 1;
    });
    return $difNotes;
}
