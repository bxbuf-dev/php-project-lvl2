<?php
namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\getDifference;
use function Differ\Differ\convertToString;
use function PHPUnit\Framework\assertEquals;

class GenDiffTest extends TestCase
{
    private $data1 = [
        'follow' => false,
        'host' => 'hexlet.io',
        'proxy' => "112.235.25.18",
        'timeout' => 20
    ];
    private $data2 = [
        'host' => 'hexlet.io',
        'timeout' => 50,
        'verbose' => true
    ];
    private $diffData = [
        ['name' => 'follow', 'stat' => '-', 'value' => false],
        ['name' => 'host', 'stat' => ' ', 'value' => 'hexlet.io'],
        ['name' => 'proxy', 'stat' => '-', 'value' => '112.235.25.18'],
        ['name' => 'timeout', 'stat' => '-', 'value' => 20],
        ['name' => 'timeout', 'stat' => '+', 'value' => 50],
        ['name' => 'verbose', 'stat' => '+', 'value' => true]
    ];
    public function testGetDifference(): void
    {
        $this->assertEquals(
            getDifference($this->data1, $this->data2),
            $this->diffData
        );
        $this->assertEquals(
            getDifference([], []),
            []
        );
    }
    public function testConvertToString(): void
    {
        $diffString = file_get_contents(__DIR__ . '/fixtures/FilesDifference.txt');
        assertEquals(
            convertToString($this->diffData),
            $diffString
        );
    }
}
