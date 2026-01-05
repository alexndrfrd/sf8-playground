<?php

namespace App\Service;

class ExampleHelperService
{
    public function formatData(string $data): string
    {
        return trim($data);
    }

    public function validateData(string $data): bool
    {
        return !empty($data) && strlen($data) > 0;
    }

    public function transformData(string $data): string
    {
        return str_replace(' ', '_', $data);
    }
}

