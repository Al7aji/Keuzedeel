<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register()
    {    
        
        return view('Auth.register');
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
        
        $user = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
             
        ]);

         auth()->login($user);

        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');

    }
    public function login()
    {   
        return view('Auth.login');
    }

    public function loginUser(Request $request){

        // Handle user login logic here
        $validate = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);


        if(Auth::attempt($validate)){
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');{
        };


        throw ValidationException::withMessages([
            'email' => 'E-mailadres of wachtwoord is onjuist.',
            'password'=> ' wachtwoord is onjuist.',
        ]);
    
    }
   
    }
     public function logout(Request $request){
        Auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}