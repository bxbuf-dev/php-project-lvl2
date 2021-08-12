<?php

namespace Differ\Differ\Formatters;

use function Differ\Differ\Formatters\Stylish\stylish;
use function Differ\Differ\Formatters\Plain\plain;
use function Differ\Differ\Formatters\Json\json;

function getFormatted(array $difNotes, string $format): string
{
    switch ($format) {
        case "stylish":
            return stylish($difNotes);
        case "plain":
            return plain($difNotes);
        case "json":
            return json($difNotes);
    }
    return "\nFormat {$format} is coming soon... probably\n";
}
