<?php

namespace Tests\Unit;


use Exception;
use PHPUnit\Framework\TestCase;
use App\Parsers\CSVParser;

class CSVParserTest extends TestCase
{
    protected string $testFilePath;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a temporary CSV file for testing
        $this->testFilePath = sys_get_temp_dir() . '/test.csv';
        file_put_contents($this->testFilePath, <<<CSV
        id,name,email,phone,address,city,state,zip
        1,John Doe,john@example.com,1234567890,123 Main St,Anytown,CA,12345
        2,Jane Doe,jane@example.com,0987654321,456 Elm St,Somewhere,NY,67890
        3,Invalid Row,missing fields
        4,Bob Smith,bob@example.com,5555555555,789 Oak St,Nowhere,TX,54321
        CSV);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up the temporary file
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    public function testParseValidCSV(): void
    {
        $parser = new CSVParser();
        $parsedData = $parser->parse($this->testFilePath);

        $expectedData = [
            ['1', 'John Doe', 'john@example.com', '1234567890', '123 Main St', 'Anytown', 'CA', '12345'],
            ['2', 'Jane Doe', 'jane@example.com', '0987654321', '456 Elm St', 'Somewhere', 'NY', '67890'],
            ['4', 'Bob Smith', 'bob@example.com', '5555555555', '789 Oak St', 'Nowhere', 'TX', '54321'],
        ];

        $this->assertCount(3, $parsedData);
        $this->assertEquals($expectedData, $parsedData);
    }

    public function testParseWithEmptyFile(): void
    {
        file_put_contents($this->testFilePath, "id,name,email,phone,address,city,state,zip\n");

        $parser = new CSVParser();
        $parsedData = $parser->parse($this->testFilePath);

        $this->assertEmpty($parsedData);
    }

    public function testParseWithMissingFile()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("File not found: missing_file.csv");

        $parser = new CSVParser();
        $parser->parse('missing_file.csv');
    }
}
