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
