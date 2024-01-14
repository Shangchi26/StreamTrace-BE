<?php

namespace App\Http\Controllers;

use App\Models\Review;

class ReviewController extends Controller
{
    //
    public function index($id)
    {
        $reviews = Review::with('user:id,full_name,avatar,created_at')->where("video_id", $id)->latest()->get();

        return response()->json($reviews);
    }
    public function delete($id)
    {
        $review = Review::find($id);
        $review->delete();

        return response()->json("Delete successfully.");
    }
    public function getAll($id)
    {
        $reviews = Review::whereHas('video', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->with('user:id,full_name,avatar,created_at')
            ->with('video:id,name')
            ->latest()->get();

        return response()->json($reviews);
    }
}
