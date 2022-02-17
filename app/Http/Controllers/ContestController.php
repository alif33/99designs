<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['index', 'find', 'show']]);
    }

    public function index()
    {
        return Contest::where(
            'status' , 1
        )->get();
    }

    public function find( $slug )
    {   
    
        return Contest::where([
            'slug' => $slug,
            'status' => 1
        ]
           
        )->orderBy('id', 'DESC')->first();
    }
    public function show( $id )
    {   
    
        return Contest::where([
            'id' => $id,
            'status' => 1
        ])->orderBy('id', 'DESC')->first();
    }

    public function fetch()
    {
        return Contest::where(
            'posted_by' , Auth::user()->id
        )->orderBy('id', 'DESC')->get();
    }


    public function store(Request $request)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                'contest_title' => 'required|string|between:2,500|unique:contests',
                'contest_description' => 'string|between:2,10000',
                'image' => 'mimes:png,jpg,jpeg,gif|max:2048',
                'contest_prize' => 'integer',
                'start_date' => 'required|string',
                'end_date' => 'required|string',
            ]
        );


        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        if ($image = $request->file('image')) {
            $contest = Contest::create(
                array_merge(
                    [   
                        'slug' => Str::slug($request->input('contest_title'), '-'),
                        'contest_image' => $image->store('contests', 'public'),
                        'posted_by' => Auth::user()->id
                    ],
                    $validator->validated()
                )
            );
    
            if($contest){
                return response()->json(
                    [   
                        'success'=>true,
                        'message'=>'Contest created successfully !'
                    ],
                    201
                );    
            }
        }else{
            $contest = Contest::create(
                array_merge(
                    [   
                        'slug' => Str::slug($request->input('contest_title'), '-'),
                        'posted_by' => Auth::user()->id
                    ],
                    $validator->validated()
                )
            );
    
            if($contest){
                return response()->json(
                    [   
                        'success'=>true,
                        'message'=>'Contest created successfully !'
                    ],
                    201
                );    
            }
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
