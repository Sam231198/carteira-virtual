<?php

namespace App\Http\Controllers;

use App\Services\ContaService;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    public function __construct(private ContaService $contaService)
    {
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $conta = $this->contaService->login($credentials['email'], $credentials['password']);
            return response()->json($conta);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function createConta(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer'
        ]);

        try {
            $conta = $this->contaService->createUserWithWallet($data['user_id']);
            return response()->json($conta, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getConta(Request $request)
    {
        try {
            $user = $request->user(); // Ensure the user is authenticated
            $conta = $this->contaService->getUserById($user->id);
            return response()->json($conta);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
