<?php

namespace App\Service;

use App\ValueObject\Damage;

class ExampleService
{
    public function __construct(
        private readonly ExampleHelperService $helperService
    ) {
    }

    public function processData(string $data): string
    {
        // Use injected helper service
        if (!$this->helperService->validateData($data)) {
            throw new \InvalidArgumentException('Data cannot be empty');
        }

        $formatted = $this->helperService->formatData($data);
        $transformed = $this->helperService->transformData($formatted);
        
        return strtoupper($transformed) . ' (processed)';
    }

    public function calculateDamage(int $base): Damage
    {
        // Example using Value Object
        return new Damage($base);
    }
}

