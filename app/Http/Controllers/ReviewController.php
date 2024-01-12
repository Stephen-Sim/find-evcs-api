<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function getReviewByStationId($station_id)
    {
        $reviews = DB::table('reviews')
            ->where('station_id', $station_id)
            ->get();

        return $reviews;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'rating' =>  'required|integer|between:1,5',
            'description' =>  'required|string|max:1000',
            'guest_name' =>  'required|string|max:255',
            'station_id' =>  'required|integer|exists:stations,id',
        ]);

        DB::table('reviews')->insert([
            'rating' => $request->rating,
            'description' => $request->description,
            'guest_name' => $request->guest_name,
            'station_id' => $request->station_id,
        ]);

        return response(null, 200);
    }
}
