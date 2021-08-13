<?php

namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\Formatters\Stylish\stylish;
use function Differ\Differ\Formatters\Plain\plain;
use function Differ\Differ\Formatters\Stylish\parseDifNote;

class FormattersTest extends TestCase
{
    private $flatDiffData = [
        ['name' => 'follow', 'stat' => '-', 'value' => false],
        ['name' => 'host', 'stat' => '+', 'value' => 'hexlet.io'],
        ['name' => 'proxy', 'stat' => '-', 'value' => '123.234.53.22'],
        ['name' => 'timeout', 'stat' => '-', 'value' => 50],
        ['name' => 'timeout', 'stat' => '+', 'value' => 20],
        ['name' => 'verbose', 'stat' => '+', 'value' => true]
    ];
    private $flatDiffStylish =
        "{" . PHP_EOL .
        "  - follow: false" . PHP_EOL .
        "  + host: hexlet.io" . PHP_EOL .
        "  - proxy: 123.234.53.22" . PHP_EOL .
        "  - timeout: 50" . PHP_EOL .
        "  + timeout: 20" . PHP_EOL .
        "  + verbose: true" . PHP_EOL .
        "}";

    private $flatDiffPlain =
        "Property 'follow' was removed" . PHP_EOL .
        "Property 'host' was added with value: 'hexlet.io'" . PHP_EOL .
        "Property 'proxy' was removed" . PHP_EOL .
        "Property 'timeout' was updated. From 50 to 20" . PHP_EOL .
        "Property 'verbose' was added with value: true";
    private $recDiffData = [
            ['name' => 'host', 'stat' => '+', 'value' => 'hexlet.io'],
            ['name' => 'misc',
                'stat' => ' ',
                'value' => [
                    ['name' => 'follow', 'stat' => '-', 'value' => false],
                    [
                        'name' => 'timeout',
                        'stat' => ' ',
                        'value' => [
                            ['name' => 'isNested', 'stat' => '-', 'value' => 'true'],
                            ['name' => 'verbose', 'stat' => '+', 'value' => true]
                        ],
                    ]
                ]
            ],
            ['name' => 'proxy', 'stat' => '-', 'value' => '122.122.122.122'],
        ];

    private $recDiffStylish =
        "{" . PHP_EOL .
        "  + host: hexlet.io" . PHP_EOL .
        "    misc: {" . PHP_EOL .
        "      - follow: false" . PHP_EOL .
        "        timeout: {" . PHP_EOL .
        "          - isNested: true" . PHP_EOL .
        "          + verbose: true" . PHP_EOL .
        "        }" . PHP_EOL .
        "    }" . PHP_EOL .
        "  - proxy: 122.122.122.122" . PHP_EOL .
        "}";

    private $recDiffPlain =
        "Property 'host' was added with value: 'hexlet.io'" . PHP_EOL .
        "Property 'misc.follow' was removed" . PHP_EOL .
        "Property 'misc.timeout.isNested' was removed" . PHP_EOL .
        "Property 'misc.timeout.verbose' was added with value: true" . PHP_EOL .
        "Property 'proxy' was removed";


    private $recDiffTestPlain = 
"Property 'group4.isNested' was updated. From false to 'none'
Property 'group4.key' was added with value: false
Property 'group4.nest.bar' was updated. From '' to 0
Property 'group4.nest.isNested' was removed
Property 'group4.someKey' was added with value: true
Property 'group4.type' was updated. From 'bas' to 'bar'";
    private $recDiffTest = [
        [
            'name' => 'group4',
            'stat' => ' ',
            'value' => [
                ['name' => 'isNested', 'stat' => '-', 'value' => false],
                ['name' => 'isNested', 'stat' => '-', 'value' => 'none'],
                ['name' => 'key', 'stat' => '+', 'value' => false],
                [
                    'name' => 'nest',
                    'stat' => ' ',
                    'value' => [
                        ['name' => 'bar', 'stat' => '-', 'value' => ''],
                        ['name' => 'bar', 'stat' => '+', 'value' => 0],
                        ['name' => 'isNested', 'stat' => '-', 'value' => true]
                    ]
                ],
                ['name' => 'someKey', 'stat' => '+', 'value' => true],
                ['name' => 'type', 'stat' => '-', 'value' => 'bas'],
                ['name' => 'type', 'stat' => '+', 'value' => 'bar']
            ]
        ]
    ];

    public function testStylish(): void
    {
        $this->assertEquals(
            $this->flatDiffStylish,
            stylish($this->flatDiffData)
        );
        $this->assertEquals(
            $this->recDiffStylish,
            stylish($this->recDiffData)
        );
    }

    public function testParseDifNote(): void
    {
        $difNote = ['name' => 'host', 'stat' => '+', 'value' => 'hexlet.io'];
        $difExp = "+ host: hexlet.io";
        $this->assertEquals(
            $difExp,
            parseDifNote($difNote)
        );
    }

    public function testPlain(): void
    {
        $this->assertEquals(
            $this->flatDiffPlain,
            plain($this->flatDiffData)
        );
        $this->assertEquals(
            $this->recDiffTestPlain,
            plain($this->recDiffTest)
        );
        $this->assertEquals(
            $this->recDiffPlain,
            plain($this->recDiffData)
        );
    }
}
