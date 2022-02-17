<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['login', 'register']]);

    }

    protected function guard()
    {
        return Auth::guard('user');

    }

    protected function respondWithToken($token)
    {
        return response()->json(
            [   
                'success'       => true,
                'user'          => $this->guard()->user(),
                'token'          => $token,
                'token_type'     => 'bearer',
                'token_validity' => ($this->guard()->factory()->getTTL() * 60),
            ]
        );

    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'    => 'required|email',
                'password' => 'required|string|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $token_validity = (24 * 60);

        $this->guard()->factory()->setTTL($token_validity);

        if (!$token = $this->guard()->attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Creadentials'
            ], 401);
        }

        return $this->respondWithToken($token);

    }


    public function register(Request $request)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'required|string|between:2,100',
                'email'    => 'required|email|unique:users',
                'password' => 'required|confirmed|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );

        return response()->json(['message' => 'User created successfully', 'user' => $user]);

    }

    public function profile(Request $request)
    {   
        // return $request->name;
        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'string|between:2,100',
                'user_bio'    => 'string|between:2,500',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }


        if ($img = $request->file('image')) {

            $update = User::where('id', $this->guard()->user()->id)
            ->update(
                array_merge(
                    [   
                        'user_image' => $img->store('contests', 'public'),
                    ],
                    $validator->validated()
                )
            );

            if($update){
                return response()->json(
                    [   
                        'success' => true,
                        'user' => User::where('id', $this->guard()->user()->id)->first(),
                        'message' => 'User updated successfully !'
                    ],
                    201
                );    
            }
        }else{
            $update = User::where('id', $this->guard()->user()->id)
            ->update($validator->validated());
            
            if($update){
                return response()->json(
                    [   
                        'success' => true,
                        'user' => User::where('id', $this->guard()->user()->id)->first(),
                        'message' => 'User updated successfully !'
                    ],
                    201
                );    
            }
           
        }

    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'User logged out successfully']);

    }

    public function verify()
    {   
        if (!$this->guard()->user()->hasVerifiedEmail()) {
            $this->guard()->user()->markEmailAsVerified();
        }
    
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }
}
