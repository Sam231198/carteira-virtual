<?php

namespace App\Entities;

class WalletEntity
{
    public function __construct(
        public ?int $id,
        public ?int $user_id,
        public ?float $balance
    ) {
        // Initialization code if needed
    }

    static public function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            user_id: $data['user_id'] ?? 0,
            balance: $data['balance'] ?? 0.0
        );
    }
}