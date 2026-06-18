<?php

namespace App\Repositories;

use App\Entities\UserEntity;
use App\Models\User;

class UserRepository
{
    public function __construct(private User $user)
    {
        // Initialization code if needed
    }

    public function getUserById($id): ?UserEntity
    {
        $user = $this->user->find($id);

        return UserEntity::fromArray($user->toArray());
    }

    public function createUser(UserEntity $data): UserEntity
    {
        $user = $this->user->create($data);

        return UserEntity::fromArray($user->toArray());
    }

    public function updateUser($id, UserEntity $data): UserEntity
    {
        $user = $this->user->find($id);

        if (!$user) {
            throw new \Exception("User not found");
        }

        $user->update($data);

        return UserEntity::fromArray($user->toArray());
    }

    public function deleteUser($id): bool
    {
        $user = $this->user->find($id);

        if (!$user) {
            throw new \Exception("User not found");
        }

        return $user->delete();
    }
}