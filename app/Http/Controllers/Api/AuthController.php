<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        //validasi request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // cek, jika ada data user
        $user = User::where('email', $request->email)->first();
        
        // jika data user tidaka ada
        if (!$user) {
            # code...
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // jika password user salah
        if (!Hash::check($request->password, $user->password)) {
            # code... status codenya 401
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // generate (buat) token, keynya auth-token
        $token = $user->createToken('auth-token')->plainTextToken;

        //menampilkan json
        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => $user
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function logout(Request $request)
    {
        //hapus token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged Out'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
