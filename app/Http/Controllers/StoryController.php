<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['index', 'search', 'random', 'likes', 'find', 'findByTag']]);
    }

    public function index()
    {   

        return DB::table('stories')
            ->join('users', 'stories.added_by', '=', 'users.id')
            ->select('stories.*','users.name')
            ->get();

    }
    
    public function random()
    {   
        return DB::table('stories')
                ->join('users', 'stories.added_by', '=', 'users.id')
                ->select('stories.*','users.name')
                ->inRandomOrder()
                ->take(5)
                ->get();
    }

    public function likes($id)
    {
        return Story::find($id)
                ->likers()
                ->get(); 
    }

    public function like($id)
    {
        if(Auth::user()->like(Story::find($id))){
            return response()->json(
                [   
                    'success' => true,
                    'message' => 'Liked 👍'
                ],
                201
            );  
        }
    }

    public function unlike($id)
    {
        if(Auth::user()->unlike(Story::find($id))){
            return response()->json(
                [   
                    'success' => true,
                    'message' => 'Unliked 👎'
                ],
                201
            );  
        }
    }

    public function bookmarks()
    {   
       return Auth::user()->getFavoriteItems(Story::class)->get();
       
    }
    public function bookmark($id)
    {   
        Auth::user()->favorite(Story::find($id));
        if(DB::table('favorites')
        ->where([
            'user_id' => Auth::user()->id,
            'favoriteable_id' => $id
        ])
        ->get()){
            return response()->json(
                [   
                    'success' => true,
                    'message' => 'Bookmarked 🔖'
                ],
                201
            );  
        }
    }

    public function hasbookmarked($id)
    {   
        if (Auth::user()->hasFavorited(Story::find($id))) {
            return response()->json(
                [   
                    'success' => true,
                ],
                200
            );  
        }
    }

    public function unbookmark($id)
    {   
        Auth::user()->unfavorite(Story::find($id));
        $count = DB::table('favorites')
        ->where([
            'user_id' => Auth::user()->id,
            'favoriteable_id' => $id
        ])
        ->count();

        if($count==0){
            return response()->json(
                [   
                    'success' => true,
                    'message' => 'Remove from bookmarked 🔖'
                ],
                201
            );  
        }
    }

    public function fetch()
    {
        return Story::where(
            'added_by', Auth::user()->id
        )->orderBy('id', 'DESC')->get();
    }

    public function find($slug)
    {   
        return Story::where('slug', $slug)
        ->orderBy('id', 'DESC')
        ->with(['tags'])
        ->first();
    }

    public function findByTag($slug)
    {
        return Story::with(['tags'])
                ->whereHas('tags', function ($query) use ($slug) {
                    $query->where('tag_slug', $slug);
                })
                ->get();
    }

    public function search($search)
    {   
       return Story::where('title', 'LIKE', "%$search%")
            ->orderBy('id', 'DESC')->get();

    }


    public function store(Request $request)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|between:2,500|unique:stories',
                'details' => 'required|string|between:2,1000000',
                'summary' => 'required|string|between:2,500',
                'image' => 'mimes:png,jpg,jpeg,gif|max:2048',
                'contest_id' => 'required|integer',
                'category_id' => 'required|integer',
                'adult' => 'boolean'
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
                'story_image'   => $image->store('stories', 'public'),
                'slug'    => Str::slug( $request->input('title'), '-'),
                'added_by' => Auth::user()->id
            ];
    
            $story = Story::create(
                array_merge(
                    $another,
                    $validator->validated()
                )
            );

            $story->tags()->attach($request->tags);

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
                'added_by' => Auth::user()->id
            ];
    
            $story = Story::create(
                array_merge(
                    $another,
                    $validator->validated()
                )
            );

            $story->tags()->attach($request->tags);

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

    public function update($id, Request $request)
    {   

        $validator = Validator::make(
            $request->all(),
            [
                [
                    'title' => 'string|between:2,30|unique:stories',
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

            Story::findOrFail($id)->tags()->sync($request->tags);

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
