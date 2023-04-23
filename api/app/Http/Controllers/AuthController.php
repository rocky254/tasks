<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    /**
     * Registration
     */
    public function register(RegisterRequest $request)
    {
        try
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
    
            $response['user'] = $user->name;
            $response['access_token'] = $user->createToken(config('app.name'))->accessToken;
    
            return $this->successResponse($response, 'User Registered Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in AuthController.register', $e->getMessage(), 500);
        }
    }

    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        try
        {
            $data = [
                'email' => $request->email,
                'password' => $request->password,
            ];
    
            if (auth()->attempt($data))
            {
                $user = auth()->user();
                $response['user'] = $user;
                $response['access_token'] = $user->createToken(config('app.name'))->accessToken;
                
                return $this->successResponse($response, 'User Loggged In Successfully');
            }
            else
            {
                return $this->errorResponse('Unauthorized', 401);
            }
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in AuthController.login', $e->getMessage(), 500);
        }
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request, $user_id)
    {
        try
        {
            if($request->password)
            {
                $request->validate([
                    'name' => 'string|between:2,100',
                    'email' => 'string|email|max:100',
                    'password' => 'string|confirmed|min:6',
                    'password_confirmation' => 'string|min:6',
                    'current_password' => 'string|min:6',
                ]);
            }
            else
            {
                $request->validate([
                    'name' => 'string|between:2,100',
                    'email' => 'string|email|max:100',
                ]);
            }
    
            $user = User::findOrFail($user_id);
    
            if($request->password)
            {
                $new_password = bcrypt($request->password); 
    
                $data = [
                    'email' => $request->email,
                    'password' => $request->current_password,
                ];
    
                if(auth()->attempt($data))
                {
                    $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => $new_password,
                    ]);
                }
                else
                {
                    return $this->errorResponse('Current Password Incorrect!', 500);
                }
            }
            else
            {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);
            }

            $response['user'] = $user;
            return $this->successResponse($response, 'Profile Updated Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in AuthController.updateProfile', $e->getMessage(), 500);
        }
    }
}
