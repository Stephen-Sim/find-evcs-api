<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CalculateDistanceHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StationController extends Controller
{
    public function get()
    {
        $stations = DB::table('stations')
            ->join('admins', 'admins.id', '=', 'stations.admin_id')
            ->leftJoin('reviews', 'stations.id', '=', 'reviews.station_id')
            ->select('stations.*', 'admins.username', DB::raw('COALESCE(AVG(reviews.rating), 0) as avg_rating'))
            ->groupBy('stations.id')
            ->get();

        return $stations;
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' =>  'required|string|max:255',
            'address' =>  'required|string|max:255',
            'total_charging_stations'  =>  'required|integer|min:0',
            'image'  =>  'required|string',
            'latitude'  =>  'required|numeric|between:-90,90',
            'longitude'  =>  'required|numeric|between:-180,180',
            'admin_id'  =>  'required|integer|exists:admins,id',
        ]);

        DB::table('stations')->insert([
            'name' => $request->name,
            'address' => $request->address,
            'total_charging_stations' => $request->total_charging_stations,
            'image' => $request->image,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'admin_id' => $request->admin_id,
        ]);

        return response(null, 200);
    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' =>  'required|string|max:255',
            'address' =>  'required|string|max:255',
            'total_charging_stations'  =>  'required|integer|min:0',
            'image'  =>  'required|string',
            'latitude'  =>  'required|numeric|between:-90,90',
            'longitude'  =>  'required|numeric|between:-180,180',
        ]);

        DB::table('stations')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'address' => $request->address,
                'total_charging_stations' => $request->total_charging_stations,
                'image' => $request->image,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

        return response(null, 200);
    }

    public function getById($id)
    {
        $station = DB::table('stations')
            ->join('admins', 'admins.id', 'stations.admin_id')
            ->leftJoin('reviews', 'stations.id', '=', 'reviews.station_id')
            ->select('stations.*', 'admins.username', DB::raw('COALESCE(AVG(reviews.rating), 0) as avg_rating'))
            ->groupBy('stations.id')
            ->where('stations.id', $id)
            ->first();

        return $station;
    }

    public function delete($id)
    {
        DB::table('stations')->where('id', $id)->delete();

        return response(null, 200);
    }

    public function getNearByStations(Request $request)
    {
        $this->validate($request, [
            'current_latitude' => 'required|numeric|between:-90,90',
            'current_longitude' => 'required|numeric|between:-180,180',
        ]);

        $stations = DB::table('stations')
            ->join('admins', 'admins.id', '=', 'stations.admin_id')
            ->leftJoin('reviews', 'stations.id', '=', 'reviews.station_id')
            ->select('stations.*', 'admins.username', DB::raw('COALESCE(AVG(reviews.rating), 0) as avg_rating'))
            ->groupBy('stations.id')
            ->get();

        $nearbyStations = [];

        foreach ($stations as $station) {
            $distance = CalculateDistanceHelper::calculateDistance(
                $request->current_latitude,
                $request->current_longitude,
                $station->latitude,
                $station->longitude
            );

            if ($distance <= 10) {
                $station->distance = $distance;
                array_push($nearbyStations, $station);
            }
        }

        usort($nearbyStations, function ($a, $b) {
            return $a->distance <=> $b->distance;
        });

        return $nearbyStations;
    }
}
