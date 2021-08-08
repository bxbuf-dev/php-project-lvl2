<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\getDifference;
use function Differ\Differ\Formatters\Stylish\stylish;
use function Differ\Differ\Formatters\Stylish\parseDifNote;
use function Differ\Differ\DifStructure\setDifNote;
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

    public function testGetDifference(): void
    {

        $this->assertEquals(
            $this->flatDiffData,
            getDifference($this->flatJson1, $this->flatJson2)
        );
        $this->assertEquals(
            [],
            getDifference([], [])
        );

        $oneLevelData1 = [
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
        $oneLevelData2 = [
            'host' => 'hexlet.io',
            'misc' => [
                'verbose' => true,
                'timeout' => 20
            ]
        ];
        $oneLevelDataDiff = [
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
                    ['name' => 'value', 'value' => 25, 'stat' => ' '],
                    ['name' => 'subType', 'value' => 'some type', 'stat' => ' ']
                ]
            ]
        ];
        $this->assertEquals(
            $oneLevelDataDiff,
            getDifference($oneLevelData1, $oneLevelData2)
        );
        $difNotesUnsorted = [
            ['name' => 'timeout', 'stat' => '+', 'value' => 20],
            ['name' => 'follow', 'stat' => '-', 'value' => false],
            ['name' => 'verbose', 'stat' => '+', 'value' => true],
            ['name' => 'timeout', 'stat' => '-', 'value' => 50]
        ];
        $difNotesSorted = [
            ['name' => 'follow', 'stat' => '-', 'value' => false],
            ['name' => 'timeout', 'stat' => '-', 'value' => 50],
            ['name' => 'timeout', 'stat' => '+', 'value' => 20],
            ['name' => 'verbose', 'stat' => '+', 'value' => true]
        ];
        $this->assertEquals(
            $difNotesSorted,
            sortDifNotes($difNotesUnsorted)
        );
    }

    public function testGenDiff()
    {
        $jsonPath1 = __DIR__ . '/fixtures/file1.json';
        $jsonPath2 = __DIR__ . '/fixtures/file2.json';
        $this->assertEquals(
            $this->getDiffString(),
            genDiff($jsonPath1, $jsonPath2)
        );
        $this->assertEquals(
            "\nFormat json is coming soon... probably\n",
            genDiff($jsonPath1, $jsonPath2, "json")
        );
        $yamlPath1 = __DIR__ . '/fixtures/file1.yaml';
        $yamlPath2 = __DIR__ . '/fixtures/file2.yml';
        $this->assertEquals(
            $this->getDiffString(),
            genDiff($yamlPath1, $yamlPath2, "stylish")
        );

        $jsonPath1 = __DIR__ . '/fixtures/C_file1.json';
        $jsonPath2 = __DIR__ . '/fixtures/C_file2.json';
        $this->assertEquals(
            $this->getDiffStylish(),
            genDiff($jsonPath1, $jsonPath2)
        );

        $jsonPath1 = __DIR__ . '/fixtures/C_file1.yml';
        $jsonPath2 = __DIR__ . '/fixtures/C_file2.yaml';
        $this->assertEquals(
            $this->getDiffStylish(),
            genDiff($jsonPath1, $jsonPath2)
        );
    }

    public function testSetDiffNote(): void
    {
        $key = 'testKey0';
        $value = [
            'testKey0-0' => 'value0-0',
            'testKey0-1' => $this->flatJson1];
        $stat = '+';

        $expRes = [
            'name' => 'testKey0',
            'stat' => '+',
            'value' => [
                ['name' => 'testKey0-0', 'value' => 'value0-0', 'stat' => ' '],
                [
                    'name' => 'testKey0-1',
                    'stat' => ' ',
                    'value' => [
                        ['name' => 'follow', 'value' => false, 'stat' => ' '],
                        ['name' => 'host', 'value' => 'hexlet.io', 'stat' => ' '],
                        ['name' => 'proxy', 'value' => "123.234.53.22", 'stat' => ' '],
                        ['name' => 'timeout', 'value' => 50, 'stat' => ' ']
                    ]
                ]
            ]
        ];
        $this->assertEquals(
            $expRes,
            setDifNote($key, $value, $stat)
        );
    }
    public function testSortDifNotes(): void
    {
        $unsorted = [
                    ['name' => 'foo', 'stat' => ' ', 'value' => 'bar'],
                    ['name' => 'baz', 'stat' => '+', 'value' => 'bars'],
                    ['name' => 'baz', 'stat' => '-', 'value' => 'bas'],
                    ['name' => 'nest', 'stat' => '+', 'value' => 'str']
        ];
        $sorted = [
                    ['name' => 'baz', 'stat' => '-', 'value' => 'bas'],
                    ['name' => 'baz', 'stat' => '+', 'value' => 'bars'],
                    ['name' => 'foo', 'stat' => ' ', 'value' => 'bar'],
                    ['name' => 'nest', 'stat' => '+', 'value' => 'str']
        ];
        $this->assertEquals(
            $sorted,
            sortDifNotes($unsorted)
        );
    }
    private function getDiffString(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/FilesDifference.txt');
    }

    private function getDiffStylish(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/C_FilesDifference.txt');
    }
}
