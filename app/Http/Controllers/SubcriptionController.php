<?php

namespace App\Http\Controllers;

use App\Models\Subcription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubcriptionController extends Controller
{
    //
    public function checkSubcription($user_id, $provider_id)
    {
        $existingSubcription = Subcription::where('user_id', $user_id)
            ->where('provider_id', $provider_id)->exists();

        return response()->json($existingSubcription);
    }
    public function addSubcription(Request $request)
    {
        $user_id = $request->input('user_id');
        $provider_id = $request->input('provider_id');

        $existingSubcription = Subcription::where('user_id', $user_id)
            ->where('provider_id', $provider_id)->exists();

        if ($existingSubcription) {
            return response()->json(['error' => 'Data already exists.'], 400);
        }

        $subcription = new Subcription;
        $subcription->user_id = $user_id;
        $subcription->provider_id = $provider_id;
        $subcription->save();

        return response()->json($subcription);
    }
    public function removeSubcription(Request $request)
    {
        $user_id = $request->input("user_id");
        $provider_id = $request->input("provider_id");

        $existingSubcription = Subcription::where('user_id', $user_id)
            ->where('provider_id', $provider_id)->first();

        if (!$existingSubcription) {
            return response()->json(['error' => 'Data already exists.'], 400);
        }

        $existingSubcription->delete();

        return response()->json("Delete successfully.");
    }
    public function count($providerId)
    {
        $count = Subcription::where('provider_id', $providerId)->count();

        return response()->json($count);
    }
    public function index($id)
    {
        $subcriptions = Subcription::where('provider_id', $id)
            ->join('users', 'subcriptions.user_id', '=', 'users.id')
            ->select('subcriptions.*', 'users.full_name as user_name', 'users.avatar as user_avatar')
            ->latest()->get();

        return response()->json($subcriptions);
    }
    public function countInWeek($id)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $count = Subcription::where('provider_id', $id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        return response()->json($count);
    }
}
