<?php

namespace App\Http\Controllers;

use App\Models\FavoriteList;
use App\Models\Review;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    //
    public function getTop()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $videos = Video::orderBy('view', 'desc')
            ->get();

        return response()->json($videos);
    }
    public function getNew()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $videos = Video::orderBy('id', 'desc')
            ->get();

        return response()->json($videos);
    }
    public function recommend($id)
    {
        $videos = Video::join('subcriptions', 'videos.user_id', '=', 'subcriptions.provider_id')
            ->where('subcriptions.user_id', $id)
            ->where('videos.status', 1)
            ->orderBy('videos.id', 'desc')
            ->select('videos.*')
            ->get();

        return response()->json($videos);
    }
    public function detail($id)
    {
        $video = Video::find($id);
        $user = User::where('id', $video->user_id)->first();
        $video->user = $user->user_name;
        $video->user_avatar = $user->avatar;
        $video->user_full_name = $user->full_name;
        $likesCount = $video->likes()->count();
        $video->like = $likesCount;

        return response()->json($video);
    }
    public function like(Request $request, $id)
    {
        $video = Video::find($id);
        $favorite = new FavoriteList;
        $favorite->user_id = $request->input('user_id');
        $favorite->video_id = $id;
        $favorite->save();

        return response()->json($favorite);
    }
    public function comment(Request $request)
    {
        $review = new Review;
        $review->user_id = $request->input('user_id');
        $review->video_id = $request->input('video_id');
        $review->comment = $request->input('comment');
        $review->save();

        return response()->json($review);
    }
    public function addView(Request $request)
    {
        $id = $request->input('id');
        $video = Video::find($id);
        $video->view += 1;
        $video->save();

        return response()->json($video);
    }
    public function index($id)
    {
        $videos = Video::where("user_id", $id)->latest()->get();

        return response()->json($videos);
    }
    public function admin()
    {
        $videos = Video::latest()->get();
        return response()->json($videos);
    }
    public function confirmVideo(Request $request)
    {
        $id = $request->input("id");
        $video = Video::find($id);
        if ($video->status === 1) {
            return response()->json(["message" => "Video is confirmed."], 400);
        }
        $video->status = 1;
        $video->save();

        return response()->json(["message" => "Confirm video successfully."]);
    }
    public function delete($id)
    {
        $video = Video::find($id);
        if (!$video) {
            return response()->json(['error' => 'Video not found'], 404);
        }
        FavoriteList::where('video_id', $id)->delete();
        Review::where('video_id', $id)->delete();

        $video->delete();
        return response()->json(['message' => 'Video and related records deleted successfully']);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'thumbnail' => 'required',
            'src' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $video = new Video;
        $video->user_id = $request->input("user_id");
        $video->name = $request->input("name");
        $video->description = $request->input("description");
        $video->thumbnail = $request->input("thumbnail");
        $video->src = $request->input("src");
        $video->save();

        return response()->json(["message" => "Upload video successfully"]);
    }
}
