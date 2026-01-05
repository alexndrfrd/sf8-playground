<?php

namespace App\DTO;

class ExampleDTO
{
    public ?string $input = null;
    public ?string $output = null;
    public ?\DateTimeImmutable $processedAt = null;

    public function toArray(): array
    {
        return [
            'input' => $this->input,
            'output' => $this->output,
            'processedAt' => $this->processedAt?->format('Y-m-d H:i:s'),
        ];
    }
}

