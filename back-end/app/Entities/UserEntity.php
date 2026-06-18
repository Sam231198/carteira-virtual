<?php

namespace App\Entities;

class UserEntity
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public ?string $email,
        public ?string $password,
        public ?WalletEntity $wallet
    ) {
        // Initialization code if needed
    }

    static public function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            wallet: isset($data['wallet']) ? WalletEntity::fromArray($data['wallet']) : null
        );
    }
}