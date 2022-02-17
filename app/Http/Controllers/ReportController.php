<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth:user', [ ]);
    }

    public function store(Request $request, $id)
    {       
      
        $validator = Validator::make(
            $request->all(),
            [
                'report' => 'required|string|between:2,500'
            ]
        );


        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $report = Report::create(
                    array_merge(
                        [
                            'story_id' => $id,
                            'user_id' => Auth::user()->id 
                        ],
                        $validator->validated()
                    )
                );

        if($report)
        {
            return response()->json(
                [   
                    'success' => true,
                    'message' => 'Successfully Reported.'
                ],
                201
            ); 
        }

    }
}
