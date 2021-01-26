<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function validator($data)
    {
        return Validator::make($data,[
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required']
        ]);
    }
}
