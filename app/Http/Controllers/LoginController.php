<?php

namespace App\Http\Controllers;
use Socialite;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function redirectToProvider(){

        return Socialite::driver('github')->redirect();

    }

    public function handleProviderCallback(){  
        $user = Socialite::driver('github')->stateless()->user();

        //사용자 상세정보 조회
        // OAuth 2.0 providers...
        $token = $user->token;
        $refreshToken = $user->refreshToken;
        $expiresIn = $user->expiresIn;
    
        // OAuth 1.0 providers...
        $token = $user->token;
        $tokenSecret = $user->tokenSecret;
        
        
        // All providers...
        $user->getId();
        $user->getNickname();
        $user->getEmail();
        $user->getAvatar();

        // Socialite를 사용하여 사용자 정보 가져오기
        $socialiteUser = Socialite::driver('github')->user();

        // Laravel 사용자 모델에 저장

        $user = $this->findOrCreateUser($socialiteUser);
        $user->name = $socialiteUser->nickname;
        $user->save();
        // 사용자를 로그인
        Auth::login($user);

        return redirect('/board')->with('success', 'Login successful!');
    }
    private function findOrCreateUser($socialiteUser)
    {
        // Socialite에서 가져온 사용자 정보를 기반으로 Laravel 사용자 모델에서 사용자를 찾거나 생성
        $user = User::where('email', $socialiteUser->getEmail())->first();

        if (!$user) {
            // 사용자가 없으면 새로운 사용자 생성
            $user = User::create([
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
                'password' => Hash::make($socialiteUser->getEmail())
                // 기타 필요한 사용자 속성 추가
            ]);
        }

        return $user;
    }
}
