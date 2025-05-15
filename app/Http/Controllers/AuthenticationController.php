<?php

namespace App\Http\Controllers;

use App\Models\SispModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'Username' => ['Username yang dimasukan salah.'],
            ]);
        }
        
        $data['token'] = $user->createToken($request->device_name)->plainTextToken;
        return response()->json($data, 200);
    }
}
