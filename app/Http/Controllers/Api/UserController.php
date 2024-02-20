<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function register(Request $request) {
        $requestData = $request->all();

        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'email|required|unique:users',
            'password' => 'required|string|min:6'
        ],
         [
            'name.required' => 'Name alanı boş bırakılamaz',
            'name.string' => 'Bu alan yazı olmalıdır',
            'name.max' => 'Max 255 karakter olabilir.',
            'email.required' => 'Bu alan boş bırakılamaz.',
            'email.email' => 'Email formatında olmalıdır.',
            'email.unique' => 'Zaten email kayıtlıdır.',
            'password.required' => 'Bu ala nbos bırakılamaz',
            'password.min' => 'Minimum 6 karakterden olmalıdır.'
         ]);

        $data = User::create([
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'password' =>Hash::make($requestData['password'])
        ]);
        return apiResponse('Register Success',200,$data);
    }

    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.email' => 'Email formatında olmalıdır',
            'email.required' => 'Email alanı boş bırakılmaz',
            'password.required' => 'Bu ala nbos bırakılamaz',
            'password.min' => 'Minimum 6 karakterden olmalıdır.'
        ]);

        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = auth()->user();
            $token = $user->createToken('api_case')->accessToken;
            $token_text = $user->createToken('api_case')->plainTextToken;
            return apiResponse(__('Succes login.!'),200, ['token_text' =>$token_text,'token' =>$token, 'user' =>$user]);
        }

        // $token = $user->createToken('api_case')->accessToken;
        // $token_text = $user->createToken('api_case')->plainTextToken;
        // return apiResponse(__('Success Login'),200, ['token_text' =>$token_text,'token' =>$token,'user' =>$user]);

        return apiResponse(__('UNATUHORIZED',401));
    }


    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $tokens = $user->tokens;

            foreach ($tokens as $token) {
                $token->delete();
            }

            return apiResponse(__('Başarıyla Çıkış Yapıldı.'), 200, ['user' => auth()->user()]);
        }

        return apiResponse(__('Oturum açmış bir kullanıcı bulunamadı'), 401);
    }

    public function index()
    {
        $users = User::with('todos')->get();

        return UserResource::collection($users);
    }
}
