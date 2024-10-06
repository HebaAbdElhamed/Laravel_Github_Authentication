<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GithubController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }


    public function handleGithubCallback()
    {
        try {
        
            $user = Socialite::driver('github')->user();
         
            $findUser = User::where('github_id', $user->id)->first();
         
            if($findUser){
         
                Auth::login($findUser);
        
                return redirect()->intended('dashboard');
         
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                        'name' => $user->name,
                        'github_id'=> $user->id,
                        'password' => encrypt('123456dummy')
                    ]);
        
                Auth::login($newUser);
        
                return redirect()->intended('dashboard');
            }
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
