<?php

namespace App\Entities;

class TransationEntity
{
    public function __construct(
        public ?int $id,
        public ?int $wallet_id,
        public ?float $amount,
        public ?string $description,
        public ?string $type, // 'income' or 'expense'
        public ?string $created_at,
        public ?string $updated_at,
        public ?int $wallet_transfer_id = 0,
    ) {
        // Initialization code if needed
    }

    static public function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            wallet_id: $data['wallet_id'] ?? 0,
            wallet_transfer_id: $data['wallet_transfer_id'] ?? 0,
            amount: $data['amount'] ?? 0.0,
            description: $data['description'] ?? '',
            type: $data['type'] ?? '',
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'wallet_transfer_id' => $this->wallet_transfer_id,
            'amount' => $this->amount,
            'description' => $this->description,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}