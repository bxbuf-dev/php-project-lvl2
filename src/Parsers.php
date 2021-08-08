<?php

namespace Differ\Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getDataFromFile(string $filePath): array
{
    $fileType = strstr($filePath, '.');
    switch ($fileType) {
        case ".json":
            return json_decode(file_get_contents($filePath), true);
        case ".yml" || ".yaml":
            return Yaml::parse(file_get_contents($filePath));
    }
}
