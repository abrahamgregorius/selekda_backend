<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::get();

        return response()->json([
            'comments' => $comments 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'website' => 'required',
            'comment' => 'required',
            'captcha' => 'required',
            'date' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field(s)',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Comment::create($request->all());
    
        return response()->json([
            'message' => 'Comment created',
            'comment' => $comment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::find($id);

        if(!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }

        return response()->json([
            'comment' => $comment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::find($id);

        if(!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }

        $comment->update($request->all());

        return response()->json([
            'message' => 'Comment updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);

        if(!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted'
        ]);
    }
}
