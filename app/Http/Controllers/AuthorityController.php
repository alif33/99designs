<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Story;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthorityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

    }

    protected function guard()
    {
        return Auth::guard('admin');

    }

    public function index()
    {   
        return Contest::orderBy('id', 'DESC')
        ->get();
    }

    public function active($id)
    {   
        $contest = Contest::find($id);
        $contest->status = 1;
        $update = $contest->update();
            if($update){
                return response()->json(
                    [   
                        'success' => true,
                        'message' => 'Contest activated successfully'
                    ],
                    201
                );    
            }
    }

    public function deactive($id)
    {   
        $contest = Contest::find($id);
        $contest->status = 0;
        $update = $contest->update();
            if($update){
                return response()->json(
                    [   
                        'success' => true,
                        'message' => 'Contest deactivated successfully'
                    ],
                    201
                );    
            }
    }



    public function get_stories()
    {
        return Story::where('author', 'ADMIN')
        ->orderBy('id', 'DESC')
        ->get();
    }

    
    public function active_story($id)
    {   
        $story = Story::find($id);
        $story->status = 1;
        $update = $story->update();
            if($update){
                return response()->json(
                    [   
                        'success' => true,
                        'message' => 'Story activated successfully'
                    ],
                    201
                );    
            }
    }

    public function deactive_story($id)
    {   
        $story = Story::find($id);
        $story->status = 0;
        $update = $story->update();
            if($update){
                return response()->json(
                    [   
                        'success' => true,
                        'message' => 'Story deactivated successfully'
                    ],
                    201
                );    
            }
    }


    public function add_story(Request $request)
    {  

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|between:2,30|unique:stories',
                'details' => 'string|between:2,30',
                'summary' => 'string|between:2,30',
                'tags' => 'string|between:2,30',
                'image' => 'mimes:png,jpg,jpeg,gif|max:2048',
                'category_id' => 'required|integer',
                'adult' => 'boolean',
                'status' => 'boolean'
            ]
        );


        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        if ($image = $request->file('image')) {

            $another = [
                'slug'    => Str::slug( $request->input('title'), '-'),
                'story_image'   => $image->store('stories', 'public'),
                'author'  => 'ADMIN',
            ];
    
            $story = Story::create(
                array_merge(
                    $another,
                    $validator->validated()
                )
            );
    
            if($story){
                return response()->json(
                    [   
                        'success' => true,
                        'message' => 'Story created successfully !'
                    ],
                    201
                );    
            }
        }else{
            $another = [
                'slug'    => Str::slug( $request->input('title'), '-'),
                'author'  => 'ADMIN',
            ];
    
            $story = Story::create(
                array_merge(
                    $another,
                    $validator->validated()
                )
            );
    
            if($story){
                return response()->json(
                    [   
                        'success' => true,
                        'message' => 'Story created successfully !'
                    ],
                    201
                );    
            }
        }
    }

    // public function register(Request $request)
    // {   

    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'name'     => 'required|string|between:2,100',
    //             'email'    => 'required|email|unique:admins',
    //             'password' => 'required|confirmed|min:6',
    //         ]
    //     );

    //     if ($validator->fails()) {
    //         return response()->json(
    //             [$validator->errors()],
    //             422
    //         );
    //     }

    //     $user = Admin::create(
    //         array_merge(
    //             $validator->validated(),
    //             ['password' => bcrypt($request->password)]
    //         )
    //     );

    //     return response()->json(['message' => 'Admin created successfully', 'user' => $user]);
    // }


    // public function logout()
    // {
    //     $this->guard()->logout();

    //     return response()->json(['message' => 'User logged out successfully']);

    // }

    // public function profile()
    // {
    //     return response()->json($this->guard()->user());

    // }

    // public function refresh()
    // {
    //     return $this->respondWithToken($this->guard()->refresh());
    // }
}
