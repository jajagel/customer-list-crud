<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(){
        return view ('user.login');
    }

    public function process(Request $req){
        $validated = $req->validate([
            "email"=>['required', 'email'],
            'password'=>'required'
        ]);

        if(auth()->attempt($validated)){
            $req->session()->regenerate();

            return redirect("/customer");
        }else{
            return redirect("login");
        }


    }

    public function register(){
        return view ('user.register');
    }


    public function store(Request $req){
        //dd($req);
        $validated=$req->validate([
            "name"=>['required'],
            "email"=>['required','email', Rule::unique('users','email'),],
            "password"=>'required|confirmed'
        ]);

        $validated['password']=Hash::make($validated['password']);
        $user=User::create($validated);

        return redirect("/");

    }

    public function logout(Request $req){
        auth()->logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect('login');
    }

}
