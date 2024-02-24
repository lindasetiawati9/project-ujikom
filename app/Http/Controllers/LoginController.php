<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function me() 
    {
        return response()->json(auth()->user());
    }
    
    public function loginApi()
    {
        $credentials = request(['email', 'password']);
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        return response()->json([
            'code' => 200,
            'data' => Auth::user(),
            'token' => $token,
        ]);
    }

    public function registerapi(Request $request)
    {
        $register = new User();
        $register->email = $request->email;
        $register->name = $request->name;
        $register->password = Hash::make($request['password']);
        $register->roles = 'member';
        $register->save();
        // return redirect('/login-member');
        $respon = [
            'success' => true,
            'data' => $register,
            'message' => 'Data Berhasil Di Tambah'
        ];
        return response()->json($respon, 200);
    }
}
