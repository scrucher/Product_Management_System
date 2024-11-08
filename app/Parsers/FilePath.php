<?php

namespace App\Parsers;

interface Path{
    public function getPath(string $filename): string;
}

class FilePath implements Path{
    public function getPath(string $filename): string
    {
        return base_path('public/uploads/'.$filename);
    }
}
