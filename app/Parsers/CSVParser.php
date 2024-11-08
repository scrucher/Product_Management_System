<?php
namespace App\Parsers;

interface FileParser
{
    public function parse(string $filePath): array;
}

class CSVParser implements FileParser
{
    public function parse(string $filePath): array
    {
        $contents = file_get_contents($filePath);
        $lines = explode("\n", $contents);
        $parsedData = [];

        foreach ($lines as $line) {
            $parsedData[] = explode(';', $line);
        }

        return $parsedData;
    }
}
