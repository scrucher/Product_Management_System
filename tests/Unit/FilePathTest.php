<?php
namespace Tests\Unit;

use App\Parsers\FilePath;
use InvalidArgumentException;
use Tests\TestCase;

class FilePathTest extends TestCase
{
    public function testGetPathReturnsCorrectPath()
    {
        $filePath = new FilePath();
        $filename = 'testfile.txt';

        $expectedPath = base_path('/public/uploads/' . $filename);
        $this->assertEquals($expectedPath, $filePath->getPath($filename));
    }

    public function testGetPathHandlesEmptyFilename()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Filename cannot be empty.');

        $filePath = new FilePath();
        $filePath->getPath('');
    }
}
