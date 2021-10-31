<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['index']]);
    }

    public function index()
    {
        return Contest::all();
    }

    public function store(Request $request)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|between:2,30|unique:contests',
                'description' => 'string|between:2,30',
                'image' => 'string|between:2,30',
                'status' => 'boolean',
            ]
        );


        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $author = [
            'posted_by' => Auth::user()->id
        ];

        $contest = Contest::create(
            array_merge(
                $author,
                $validator->validated()
            )
        );

        if($contest){
            return response()->json(
                ['message'=>'Contest created successfully !'],
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
                    'title' => 'string|between:2,30|unique:contests',
                    'description' => 'string|between:2,10000',
                    'image' => 'string|between:2,30',
                    'status' => 'boolean',
                ]
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $contest = Contest::findOrFail($id);

        if($contest->posted_by == Auth::user()->id)
        {
            $contest->update(
                array_merge(
                    $validator->validated()
                )
            );

            if($contest)
            {
                return response()->json(
                    ['message'=>'Contest updated successfully !'],
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
       $contest = Contest::findOrFail($id);

       if($contest)
       {
           $contest->delete();
           return response()->json(
                ['message'=>'Contest deleted successfully'],
                422
            );
       } 
    }
}
