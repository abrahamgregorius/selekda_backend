<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::get();

        return response()->json([
            'blogs' => $blogs
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
            'title' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
            'description' => 'required',
            'tags' => 'required',
            'date' => 'required',
            'author' => 'required|exists:users,id',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field(s)',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        $data['date'] = Carbon::parse($data['date']);
        
        if($request->hasFile('profile')) {
            $file = $request->file('profile');
            $file->move(public_path() . "/profile/$request->username/", "image.png");
        }

        $slug = str()->slug($request->title);
        $data['image'] = "/blog/$slug/image.png";

        $blog = Blog::create($request->all());

        return response()->json([
            'message' => 'Blog created', 
            'blog' => $blog
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::find($id);

        if(!$blog) {
            return response()->json([
                'message' => 'Blog not found'
            ], 404);
        }

        return response()->json([
            'blog' => $blog
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
        $blog = Blog::find($id);

        if(!$blog) {
            return response()->json([
                'message' => 'Blog not found'
            ], 404);
        }

        $blog->update($request->all());

        return response()->json([
            'message' => "Blog updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::find($id);

        if(!$blog) {
            return response()->json([
                'message' => 'Blog not found'
            ], 404);
        }

        $blog->delete();
        
        return response()->json([
            'message' => "Blog deleted"
        ]);
    }
}
