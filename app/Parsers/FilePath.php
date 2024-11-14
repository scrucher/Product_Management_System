<?php

namespace App\Parsers;

interface Path{
    public function getPath(string $filename): string;
}

class FilePath implements Path{
    public function getPath(string $filename): string
{
    if (empty($filename)) {
        throw new \InvalidArgumentException('Filename cannot be empty.');
    }

    return base_path('/public/uploads/' . $filename);
}
}
