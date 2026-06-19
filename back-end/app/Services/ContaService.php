<?php

namespace App\Services;

use App\Entities\UserEntity;
use App\Entities\WalletEntity;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ContaService
{
    public function __construct(
        private UserRepository $userRepository,
        private WalletRepository $walletRepository
    ) {}

    public function login(string $email, string $password): string
    {
        try {
            $user = $this->userRepository->getByEmail($email);

            if (!$user || !password_verify($password, $user->password)) {
                throw new \Exception('Invalid credentials');
            }

            if (!method_exists($user, 'createToken')) {
                throw new \Exception('Token generation not available for this user.');
            }

            return $user->{'createToken'}('auth_token')->plainTextToken;
        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createUserWithWallet(UserEntity $userData, WalletEntity $walletData): UserEntity
    {
        try {
            $userData->password = Hash::make($userData->password);
            $user = $this->userRepository->create($userData);

            $walletData->user_id = $user->id;
            $this->walletRepository->create($walletData);

            return $user;
        } catch (\Exception $e) {
            Log::error('Create user failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getUserById(int $id): ?UserEntity
    {
        try {
            $user = $this->userRepository->getById($id);
            $user->wallet = $this->walletRepository->getById($user->id);

            return $user;
        } catch (\Exception $e) {
            Log::error('Get user failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateUserWithWallet(int $id, UserEntity $userData, WalletEntity $walletData): UserEntity
    {
        try {
            $user = $this->userRepository->update($id, $userData);

            $wallet = $this->walletRepository->getById($user->id);
            if ($wallet) {
                $this->walletRepository->update($wallet->id, $walletData);
            }

            return $user;
        } catch (\Exception $e) {
            Log::error('Update user failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteUserWithWallet(int $id): bool
    {
        try {
            $wallet = $this->walletRepository->getById($id);
            if ($wallet) {
                $this->walletRepository->delete($wallet->id);
            }

            return $this->userRepository->delete($id);
        } catch (\Exception $e) {
            Log::error('Delete user failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
