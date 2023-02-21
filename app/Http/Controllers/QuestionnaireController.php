<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function store(Request $request) {
        try{

            $data = $request->validate([
                'title' =>  'required',
                'purpose' => 'required'
            ]);
            // $data['user_id'] = auth()->user()->id;
            Questionnaire::create($data);
            return response()->json($data);
        } catch(\Exception $e){
            return $e;
        }
        
    }

    // public function store(Request $request)
    // {   
    //     echo "first";
    //     $data = request()->validate([
    //         'title' =>  'required',
    //         'purpose' => 'required'
    //     ]);
    //     $data['user_id'] = auth()->user()->id;
    //     if(!$data) response()->json('req');
    //     Questionnaire::create($data);
    // }
}
