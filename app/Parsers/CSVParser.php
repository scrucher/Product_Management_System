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

        // Skip the header row by starting from index 1
        foreach (array_slice($lines, 1) as $line) {
            // Split each line by commas
            $fields = str_getcsv($line);

            // Only add valid rows to the parsed data
            if (count($fields) === 8) {
                $parsedData[] = $fields;
            }
        }

        return $parsedData;
    }
}
