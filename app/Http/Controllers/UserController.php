<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{

    public function register()
    {    
        
        return view('LoginSystem.register');
    }

    public function registerUser(Request $request)
    {
        // Handle user registration logic here
        request()->validate([
            'firstname' => ['required', 'string', 'min:4' , 'max:255'],
            'lastname' => ['required', 'string', 'min:4' , 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed','regex:/[A-Z]/','regex:/[a-z]/'],
            'password_confirmation' => ['required', 'string', 'min:6'],
            
    
        ]);
        $firstname = request()->firstname;
        $lastname = request()->lastname;
        $email = request()->email;
        $password = Hash::make(request()->password); // Hash the password before storing it
        
        User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
             
        ]);
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');

    }
    public function login()
    {   
        return view('LoginSystem.login');
    }

    public function loginUser(Request $request)
    {
        // Handle user login logic here
        request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);
        $email = request()->email;
        $password = request()->password;
        ;
        $user = User::where('email', $email)->first();
        if(!$user) {
            return redirect()->route('login')->with('error','user not found');};
        if(!Hash::check( $password , $user->password )){
            return redirect()->route('login')->with('error','Wrong password');};
        $token = $user->createToken('auth-token')->plainTextToken;
         return $token || route('index');       
    }
}