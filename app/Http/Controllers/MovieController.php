<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Services\Movie\SubtitleService;
use App\Services\MovieDataService;
use App\TheaterSubtitleManager;

class MovieController extends ApiController
{
    /**
     * Display a listing of the resource.
     * [{"title":"The Martian","poster_url":"http://poster.url"}]
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topTen = TheaterSubtitleManager::getTopTenWeekly();
        return $topTen;
        // return $sub->getToken();
    }
    public function refresh() {
        MovieDataService::getTopTen();
    }

    public function search(Request $request) {
//        dd($request);
        return "yes";
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
