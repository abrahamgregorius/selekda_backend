<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ports = Portfolio::get();

        return response()->json([
            'portfolios' => $ports
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
            'author' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field(s)',
                'errors' => $validator->errors()
            ], 422);
        }

        $portfolio = Portfolio::create($request->all());

        return response()->json([
            'message' => 'Portfolio created',
            'portfolio' => $portfolio
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $port = Portfolio::find($id);

        if(!$port) {
            return response()->json([
                'message' => 'Portfolio not found'
            ], 404);
        }

        return response()->json([
            'portfolio' => $port
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
        $port = Portfolio::find($id);

        if(!$port) {
            return response()->json([
                'message' => 'Portfolio not found'
            ], 404);
        }

        $port->update($request->all());

        return response()->json([
            'message' => 'Portfolio updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $portfolio = Portfolio::find($id);

        if(!$portfolio) {
            return response()->json([
                'message' => 'Portfolio not found'
            ], 404);
        }

        $portfolio->delete();

        return response()->json([
            'message' => 'Portfolio deleted'
        ]);
    }
}
