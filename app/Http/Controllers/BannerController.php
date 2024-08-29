<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::get();

        return response()->json([
            'banners' => $banners 
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
            'status' => 'required',
            'date' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field(s)',
                'errors' => $validator->errors()
            ], 422);
        }

        $banner = Banner::create($request->all());

        return response()->json([
            'message' => 'Banner created', 
            'banner' => $banner
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::find($id);

        if(!$banner) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json([
            'banner' => $banner
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
        $banner = Banner::find($id);

        if(!$banner) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $banner->update($request->all());

        return response()->json([
            'message' => "Banner updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::find($id);

        if(!$banner) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $banner->delete();

        return response()->json([
            'message' => 'Banner deleted'
        ]);
    }
}
