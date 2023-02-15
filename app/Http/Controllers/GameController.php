<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->size ?? 10;
        return response()->json([
            'page' => $request->page,
            'size' => $request->size,
            'totalElements' => $pageSize,
            'content' => Game::query()->paginate($pageSize)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|min:3|max:60',
            'description' => 'required|min:0|max:200'
        ]);

        $slug = Str::slug($request->get('title'));

        if(Game::where('slug', $slug)->first()) {
            return response()->json([
                'status' => 'invalid',
                'slug' => 'Game title already exists'
            ], 400);
        }

        $game = new Game;
        $game->title = $request->title;
        $game->description = $request->description;
        $game->slug = $slug;
        $game->save();

        return response()->json([
            'status' => 'success',
            'slug' => $slug
        ]);
    }

    public function file(Request $req) {
        $fileName = 'full.zip';

        $data = $req->validate([
            'zipfile' => 'file|required',
            'token' => 'required'
        ]);
        if(!$req->file('zipfile')) { 
            return response()->json([
            'failed' => 'need a zip file'
            ]);
        }
        if(!$req->token) {
            return response()->json([
                'failed' => 'need a token'
            ]);
        }
        $path = $req->file('zipfile')->move(public_path('/'), $fileName);
        $zipURL = url('/'.$fileName);
        return response()->json([
            'url' => $zipURL
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $game = Game::where('slug', $slug)->first();

        return response()->json([
            'games' => $game
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $game = Game::where('slug', $slug)->first();
        $game->update($request->all());
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $game = Game::where('slug', $slug)->first();
        $game->delete();
        return response()->json([
            
        ], 204);
    }


}
