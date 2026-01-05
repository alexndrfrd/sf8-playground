<?php

namespace App\Service;

use App\ValueObject\Damage;

class ExampleService
{
    public function processData(string $data): string
    {
        // Example service logic
        return strtoupper($data) . ' (processed)';
    }

    public function calculateDamage(int $base): Damage
    {
        // Example using Value Object
        return new Damage($base);
    }
}

