<?php

namespace App\Http\Controllers;

use App\Models\FavoriteList;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    //
    public function checkFavorite($user_id, $video_id)
    {
        $existingFavorite = FavoriteList::where('user_id', $user_id)
            ->where('video_id', $video_id)
            ->exists();

        return response()->json($existingFavorite);
    }
    public function addFavorite(Request $request)
    {
        $video_id = $request->input("video_id");
        $user_id = $request->input("user_id");

        $existingFavorite = FavoriteList::where('user_id', $user_id)
            ->where('video_id', $video_id)
            ->exists();

        if ($existingFavorite) {
            return response()->json(['error' => 'Data already exists.'], 400);
        }

        $favorite = new FavoriteList;
        $favorite->user_id = $user_id;
        $favorite->video_id = $video_id;
        $favorite->save();

        return response()->json($favorite);
    }
    public function count($videoId)
    {
        $count = FavoriteList::where('video_id', $videoId)->count();

        return response()->json($count);
    }
    public function removeFavorite(Request $request)
    {
        $video_id = $request->input("video_id");
        $user_id = $request->input("user_id");

        $existingFavorite = FavoriteList::where('user_id', $user_id)
            ->where('video_id', $video_id)
            ->first();

        if (!$existingFavorite) {
            return response()->json(['error' => 'Data already exists.'], 400);
        }
        $existingFavorite->delete();

        return response()->json("Delete successfully.");
    }
}
