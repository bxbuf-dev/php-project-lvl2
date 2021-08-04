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
    //can't use any sort functions because of the undefined order for equal array
    //make my own sort by "stat" key.
    $max = count($difNotes) - 1;
    for ($i =0; $i < $max; $i++) {
        if ($difNotes[$i]['name'] == $difNotes[$i + 1]['name']) {
            if($difNotes[$i]['stat'] == '+') {
                $tmp = $difNotes[$i];
                $difNotes[$i] = $difNotes[$i +1];
                $difNotes[$i + 1] = $tmp; 
            }
        }
    }
    return $difNotes;
}
