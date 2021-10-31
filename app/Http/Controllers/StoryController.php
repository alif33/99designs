<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['index']]);
    }

    public function index()
    {
        return Story::all();
    }

    public function store(Request $request)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|between:2,30|unique:stories',
                'details' => 'string|between:2,30',
                'summary' => 'string|between:2,30',
                'slug' => 'string|between:2,30',
                'image' => 'string|between:2,30',
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

        $author = [
            'added_by' => Auth::user()->id
        ];

        $story = Story::create(
            array_merge(
                $author,
                $validator->validated()
            )
        );

        if($story){
            return response()->json(
                ['message'=>'Story created successfully !'],
                422
            );    
        }
    }

    public function update(Request $request, $id)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                [
                    'title' => 'required|string|between:2,30|unique:stories',
                    'details' => 'string|between:2,30',
                    'summary' => 'string|between:2,30',
                    'slug' => 'string|between:2,30',
                    'image' => 'string|between:2,30',
                    'adult' => 'boolean',
                    'status' => 'boolean'
                ]
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $story = Story::findOrFail($id);

        if($story->added_by == Auth::user()->id)
        {
            $story->update(
                array_merge(
                    $validator->validated()
                )
            );

            if($story)
            {
                return response()->json(
                    ['message'=>'Story updated successfully !'],
                    422
                );    
            }
        }
        else
        {
            return response()->json(
                ['message'=>'Access denied !'],
                422
            );
        }
    }
    
    public function destory($id)
    {
       $story = Story::findOrFail($id);

       if($story)
       {
           $story->delete();
           return response()->json(
                ['message'=>'Story deleted successfully !'],
                422
            );
       } 
    }
}
