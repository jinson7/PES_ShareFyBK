<?php

namespace App\Http\Controllers;

use App\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{

    public function __construct(){
        $this->middleware('jwt', ['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    
    public function show($id){
        $publication = Publication::select('*')->where('id', $id)->get();
        return response()->json([
            'value' => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function edit(Publication $publication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publication $publication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
