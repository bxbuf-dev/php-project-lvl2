<?php
namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\getDifference;
use function Differ\Differ\Parsers\convertToString;
use function Differ\Differ\DifStructure\sortDifNotes;
use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    private $flatJson1 = [
        'follow' => false,
        'host' => 'hexlet.io',
        'proxy' => "123.234.53.22",
        'timeout' => 50
    ];
    private $flatJson2 = [
        'host' => 'hexlet.io',
        'timeout' => 20,
        'verbose' => true
    ];
    private $flatDiffData = [
        ['name' => 'follow', 'stat' => '-', 'value' => false],
        ['name' => 'host', 'stat' => ' ', 'value' => 'hexlet.io'],
        ['name' => 'proxy', 'stat' => '-', 'value' => '123.234.53.22'],
        ['name' => 'timeout', 'stat' => '-', 'value' => 50],
        ['name' => 'timeout', 'stat' => '+', 'value' => 20],
        ['name' => 'verbose', 'stat' => '+', 'value' => true]
    ];
    private $flatDiffString =
        "- follow: false" . PHP_EOL .
        "  host: hexlet.io" . PHP_EOL .
        "- proxy: 123.234.53.22" . PHP_EOL .
        "- timeout: 50" . PHP_EOL .
        "+ timeout: 20" . PHP_EOL .
        "+ verbose: true" . PHP_EOL;

    private $flatDiffFormatted =
        "{" . PHP_EOL .
        "- follow: false" . PHP_EOL .
        "  host: hexlet.io" . PHP_EOL .
        "- proxy: 123.234.53.22" . PHP_EOL .
        "- timeout: 50" . PHP_EOL .
        "+ timeout: 20" . PHP_EOL .
        "+ verbose: true" . PHP_EOL .
        "}" . PHP_EOL;

    private $oneLevelData1 = [
        'proxy' => "123.234.53.22",
        'misc' => [
            'timeout' => 50,
            'follow' => false
        ],
        'host' => 'hexlet.io',
        'type' => [
            'value' => 25,
            'subType' => 'some type'
        ]
    ];
    private $oneLevelData2 = [
        'host' => 'hexlet.io',
        'misc' => [
            'verbose' => true,
            'timeout' => 20
        ]
    ];
    private $oneLevelDataDiff = [
        ['name' => 'host', 'stat' => ' ', 'value' => 'hexlet.io'],
        ['name' => 'misc',
            'stat' => ' ',
            'value' => [
                ['name' => 'follow', 'stat' => '-', 'value' => false],
                ['name' => 'timeout', 'stat' => '-', 'value' => 50],
                ['name' => 'timeout', 'stat' => '+', 'value' => 20],
                ['name' => 'verbose', 'stat' => '+', 'value' => true]
            ]
        ],
        ['name' => 'proxy', 'stat' => '-', 'value' => '123.234.53.22'],
        ['name' => 'type',
            'stat' => '-',
            'value' => [
                'value' => 25,
                'subType' => 'some type'
            ]
        ]
    ];

    public function testGetDifference(): void
    {
        /*
        $this->assertEquals(
            $this->flatDiffData,
            getDifference($this->flatJson1, $this->flatJson2)
        );
        $this->assertEquals(
            [],
            getDifference([], [])
        );
        */
        $this->assertEquals(
            $this->oneLevelDataDiff,
            getDifference($this->oneLevelData1, $this->oneLevelData2)
        );
    }

    public function testConvertToString(): void
    {
        $this->assertEquals(
            $this->flatDiffString,
            convertToString($this->flatDiffData)
        );
    }
/*
    public function testGenDiff()
    {
        $jsonPath1 = __DIR__ . '/fixtures/file1.json';
        $jsonPath2 = __DIR__ . '/fixtures/file2.json';
        $this->assertEquals(
            genDiff($jsonPath1, $jsonPath2),
            $this->getDiffString()
        );
        $yamlPath1 = __DIR__ . '/fixtures/file1.yaml';
        $yamlPath2 = __DIR__ . '/fixtures/file2.yml';
        $this->assertEquals(
            genDiff($yamlPath1, $yamlPath2),
            $this->getDiffString()
        );

        $jsonPath1 = __DIR__ . '/fixtures/C_file1.json';
        $jsonPath2 = __DIR__ . '/fixtures/C_file2.json';
        $this->assertEquals(
            genDiff($jsonPath1, $jsonPath2),
            $this->getDiffFormatted()
        );

    }
*/
    private function getDiffString(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/FilesDifference.txt');
    }
    private function getDiffFormatted(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/C_FilesDifference.txt');
    }
}
