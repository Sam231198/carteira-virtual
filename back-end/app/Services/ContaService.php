<?php

namespace App\Services;

use App\Entities\UserEntity;
use App\Entities\WalletEntity;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Hash;

class ContaService
{
    public function __construct(
        private UserRepository $userRepository,
        private WalletRepository $walletRepository
    ) {
        // Initialization code if needed
    }

    public function login(string $email, string $password): string
    {
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        if (!method_exists($user, 'createToken')) {
            throw new \Exception('Token generation not available for this user.');
        }

        return $user->{'createToken'}('auth_token')->plainTextToken; // Or return a token or user data as needed
    }

    public function createUserWithWallet(UserEntity $userData, WalletEntity $walletData): UserEntity
    {
        // Create the user
        $userData->password = Hash::make($userData->password); // Ensure password is hashed
        $user = $this->userRepository->createUser($userData);

        // Create the wallet and associate it with the user
        $walletData->user_id = $user->id;
        $this->walletRepository->createWallet($walletData);

        return $user;
    }

    public function getUserById(int $id): ?UserEntity
    {
        $user = $this->userRepository->getUserById($id);
        $user->wallet = $this->walletRepository->getWalletById($user->id);

        return $user;
    }

    public function updateUserWithWallet(int $id, UserEntity $userData, WalletEntity $walletData): UserEntity
    {
        // Update the user
        $user = $this->userRepository->updateUser($id, $userData);

        // Update the wallet associated with the user
        $wallet = $this->walletRepository->getWalletById($user->id);
        if ($wallet) {
            $this->walletRepository->updateWallet($wallet->id, $walletData);
        }

        return $user;
    }

    public function deleteUserWithWallet(int $id): bool
    {
        // Delete the wallet associated with the user
        $wallet = $this->walletRepository->getWalletById($id);
        if ($wallet) {
            $this->walletRepository->deleteWallet($wallet->id);
        }

        // Delete the user
        return $this->userRepository->deleteUser($id);
    }
}