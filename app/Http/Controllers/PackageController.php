<?php

namespace App\Http\Controllers;

use App\Models\Package;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    //
    public function create(Request $request)
    {
        $package = new Package;
        $package->name = $request->input('name');
        $package->count = $request->input('count');
        $package->image = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $package->price = $request->input('price');
        $package->save();

        return response()->json($package);
    }
    public function index()
    {
        $package = Package::orderBy('price')->get();

        return response()->json($package);
    }
    public function update(Request $request, $id)
    {
        $package = Package::find($id);
        $package->name = $request->input("name");
        $package->price = $request->input("price");
        $package->count = $request->input("count");
        if ($request->hasFile('image')) {
            $package->image = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        }
        $package->save();

        return response()->json($package);
    }
    public function info($id)
    {
        $package = Package::find($id);

        return response()->json($package);
    }
}
