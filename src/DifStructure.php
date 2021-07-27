<?php

namespace Differ\Differ\DifStructure;

function setDifNote(string $key, $value, string $stat): array
{
    $dif = [
        'name' => $key,
        'value' => $value,
        'stat' => $stat
    ];
    return $dif;
}

function sortDifNotes($difNotes): array
{
    $name  = array_column($difNotes, 'name');
    $stat = array_column($difNotes, 'stat');
    array_multisort($name, SORT_ASC, $stat, SORT_DESC, $difNotes);
    return $difNotes;
}
