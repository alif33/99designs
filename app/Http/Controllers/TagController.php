<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index()
    {
        return Tag::orderBy('id', 'DESC')->get();
    }

    public function store(Request $request)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                'tag_name' => 'required|string|between:2,30|unique:tags',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $tag = Tag::create(
            array_merge(
                $validator->validated(),
                [   
                    'tag_slug' => Str::slug( $request->input('tag_name'), '-')                ]
            )
        );

        if($tag){
            return response()->json(
                [   
                    'success' => true,
                    'message'=>'Tag created successfully !'
                ],
                201
            );    
        }
    }

    public function toggle($id, $value)
    {   
        $tag = Tag::findOrFail($id)->update([
            '_active' => $value
        ]); 

        if($value)
        {
            return response()->json(
                [   
                    'success' => true,
                    'message'=>'Tag activated.'
                ],
                201
            );
        }else{
            return response()->json(
                [   
                    'success' => true,
                    'message'=>'Tag deactivated.'
                ],
                201
            );
        }
    }
}
