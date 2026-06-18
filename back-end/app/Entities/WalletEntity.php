<?php

namespace App\Entities;

class WalletEntity
{
    public function __construct(
        public ?int $id,
        public ?int $user_id,
        public ?string $name,
        public ?float $balance,
        public ?string $created_at,
        public ?string $updated_at,
    ) {
        // Initialization code if needed
    }

    static public function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            user_id: $data['user_id'] ?? 0,
            name: $data['name'] ?? '',
            balance: $data['balance'] ?? 0.0,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
        );
    }
}