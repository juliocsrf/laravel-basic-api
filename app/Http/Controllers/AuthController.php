<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $array = ['error' => ''];
        $validator = $this->validator($request->all());

        if($validator->fails()){
            $array['error'] = $validator->messages();
            return $array;
        }

        $email = $request->input('email');
        $password = $request->input('password');

        //Criando user
        $user = new User();
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        return $array;
    }

    public function login(Request $request)
    {
        $array = ['error' => ''];

        $creds = $request->only('email', 'password');
        if(Auth::attempt($creds)){
            $user = User::where('email', $creds['email'])->first();
            $item = time().random_int(0,9999);
            $token = $user->createToken($item)->plainTextToken;
            $array['token'] = $token;
        }else{
            $array['error'] = 'E-mail e/ou senha inválido(s)';
        }

        return $array;
    }

    public function logout(Request $request)
    {
        $array = ['error' => ''];

        //O Objeto do usuário já vem no request com base no Token enviado
        $user = $request->user();

        //Revoga TODOS os tokens
        //$user->tokens()->delete();

        //Revoga o token ATUAL
        $user->currentAccessToken()->delete();

        return $array;
    }

    public function validator($data)
    {
        return Validator::make($data,[
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required']
        ]);
    }
}
