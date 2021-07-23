<?php
namespace Differ\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\getDifference;
use function Differ\Differ\Parsers\convertToString;
use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    private $data1 = [
        'follow' => false,
        'host' => 'hexlet.io',
        'proxy' => "123.234.53.22",
        'timeout' => 50
    ];
    private $data2 = [
        'host' => 'hexlet.io',
        'timeout' => 20,
        'verbose' => true
    ];
    private $diffData = [
        ['name' => 'follow', 'stat' => '-', 'value' => false],
        ['name' => 'host', 'stat' => ' ', 'value' => 'hexlet.io'],
        ['name' => 'proxy', 'stat' => '-', 'value' => '123.234.53.22'],
        ['name' => 'timeout', 'stat' => '-', 'value' => 50],
        ['name' => 'timeout', 'stat' => '+', 'value' => 20],
        ['name' => 'verbose', 'stat' => '+', 'value' => true]
    ];
    private function getDiffString(): string
    {
        return file_get_contents(__DIR__ . '/fixtures/FilesDifference.txt');
    }
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
        $this->assertEquals(
            convertToString($this->diffData),
            $this->getDiffString()
        );
    }
    public function testGenDiff()
    {
        $jsonPath1 = __DIR__ . '/fixtures/file1.json';
        $jsonPath2 = __DIR__ . '/fixtures/file2.json';
        $this->assertEquals(
            genDiff($jsonPath1, $jsonPath2),
            $this->getDiffString()
        );
        $yamlPath1 = __DIR__ . '/fixtures/file1.yml';
        $yamlPath2 = __DIR__ . '/fixtures/file2.yml';
        $this->assertEquals(
            genDiff($yamlPath1, $yamlPath2),
            $this->getDiffString()
        );
    }
}
