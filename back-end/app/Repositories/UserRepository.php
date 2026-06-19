<?php

namespace App\Repositories;

use App\Entities\UserEntity;
use App\Models\User;

class UserRepository
{
    public function __construct(private User $user) {}

    public function getById(int $id): ?UserEntity
    {
        $user = $this->user->find($id);

        return UserEntity::fromArray($user->toArray());
    }

    public function getByEmail(string $email): ?UserEntity
    {
        $user = $this->user->where('email', $email)->first();

        return $user ? UserEntity::fromArray($user->toArray()) : null;
    }

    public function create(UserEntity $data): UserEntity
    {
        $user = $this->user->create($data);

        return UserEntity::fromArray($user->toArray());
    }

    public function update(int $id, UserEntity $data): UserEntity
    {
        $user = $this->user->find($id);

        if (!$user) {
            throw new \Exception("User not found");
        }

        $user->update($data);

        return UserEntity::fromArray($user->toArray());
    }

    public function delete(int $id): bool
    {
        $user = $this->user->find($id);

        if (!$user) {
            throw new \Exception("User not found");
        }

        return $user->delete();
    }
}