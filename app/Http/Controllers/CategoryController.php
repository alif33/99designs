<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['index']]);
    }


    public function index()
    {
        return Category::orderBy('id', 'DESC')->get();
    }

    public function store(Request $request)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                'category_name' => 'required|string|between:2,30|unique:categories',
                'category_icon' => 'string|between:2,30',
                'image' => 'mimes:png,jpg,jpeg,gif|max:2048',
            ]
        );




        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }


        if ($image = $request->file('image')) {
            
            $category = Category::create(
                array_merge(
                    $validator->validated(),
                    [   
                        'category_slug' => Str::slug( $request->input('category_name'), '-'),
                        'category_image' => $image->store('categories', 'public')
                    ]
                )
            );
    
            if($category){
                return response()->json(
                    [   
                        'success' => true,
                        'message'=>'Category created successfully !'
                    ],
                    201
                );    
            }

        }else{
            $category = Category::create(
                array_merge(
                    $validator->validated(),
                    [   
                        'category_slug' => Str::slug($request->input('category_name'), '-')
                    ]
                )
            );
    
            if($category){
                return response()->json(
                    [   
                        'success' => true,
                        'message'=>'Category created successfully !'
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
                'category_name' => 'required|string|between:2,30|unique:categories',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $category = Category::findOrFail($id)->update(
            array_merge(
                $validator->validated()
            )
        );

        if($category){
            return response()->json(
                ['message'=>'Category updated successfully !'],
                422
            );    
        }
    }
    
    public function destory($id)
    {
       $category = Category::findOrFail($id);

       if($category)
       {
           $category->delete();
           return response()->json(
                ['message'=>'Category deleted successfully'],
                422
            );
       } 
    }
}
