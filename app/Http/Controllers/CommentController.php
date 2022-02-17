<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['index']]);
    }

    public function index($id)
    {   
        return DB::table('comments')
        ->join('users', 'comments.user_id', '=', 'users.id')
        ->select('comments.*','users.name')
        ->where('story_id', $id)
        ->orderBy('id', 'DESC')
        ->get();
        // return Comment::where('story_id', $id)
        //     ->orderBy('id', 'DESC')
        //     ->get();

    }

    public function store(Request $request, $id)
    {       
      
        $validator = Validator::make(
            $request->all(),
            [
                'comment' => 'required|string|between:9,1010'
            ]
        );


        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $comment = Comment::create(
                    array_merge(
                        [
                            'story_id' => $id,
                            'user_id' => Auth::user()->id 
                        ],
                        $validator->validated()
                    )
                );

        if($comment)
        {
            return response()->json(
                [   
                    'success' => true,
                    'message' => 'Feedback added successfully'
                ],
                201
            ); 
        }

    }

}
