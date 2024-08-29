<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaderboardController extends Controller
{
    public function leaderboard_get() {
        $lb = Leaderboard::get();
        
        return response()->json([
            'leaderboard' => $lb
        ]);
        
    }
    
    public function leaderboard_post(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'score' => 'numeric|min:0|max:5'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ]);
        }

        Leaderboard::create([
            'user_id' => $request->user_id,
            'score' => $request->score,
        ]);

        return response()->json([
            'message' => 'Leaderboard created',
        ]);
    }


}
