<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //
    public function checkout(Request $request)
    {
        $order = new Order;
        $user_id = $request->input("user_id");
        $order->user_id = $user_id;
        $order->package_id = $request->input("package_id");
        $order->price = $request->input("price");
        $order->save();
        $user = User::find($user_id);
        $user->count += $request->input("count");
        $user->save();

        return response()->json(["message" => "Checkout successfully."]);
    }
    public function dashboard()
    {
        $today = Carbon::now()->toDateString();
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $startOfLastMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $startOfTwoMonthsAgo = Carbon::now()->startOfMonth()->subMonths(2)->toDateString();
        $startOfThreeMonthsAgo = Carbon::now()->startOfMonth()->subMonths(3)->toDateString();
        $startOfFourMonthsAgo = Carbon::now()->startOfMonth()->subMonths(4)->toDateString();
        $startOfFiveMonthsAgo = Carbon::now()->startOfMonth()->subMonths(5)->toDateString();
        $ordersToday = DB::table('orders')->whereDate('created_at', $today)->count();
        $ordersThisWeek = DB::table('orders')->whereDate('created_at', '>=', $startOfWeek)->count();
        $ordersThisMonth = DB::table('orders')->whereDate('created_at', '>=', $startOfMonth)->count();
        $revenueToday = DB::table('orders')->whereDate('created_at', $today)->sum('price');
        $revenueThisWeek = DB::table('orders')->whereDate('created_at', '>=', $startOfWeek)->sum('price');
        $revenueThisMonth = DB::table('orders')->whereDate('created_at', '>=', $startOfMonth)->sum('price');
        $revenueLastMonth = DB::table('orders')->whereBetween('created_at', [$startOfLastMonth, $startOfMonth])->sum('price');
        $revenueTwoMonthsAgo = DB::table('orders')->whereBetween('created_at', [$startOfTwoMonthsAgo, $startOfLastMonth])->sum('price');
        $revenueThreeMonthsAgo = DB::table('orders')->whereBetween('created_at', [$startOfThreeMonthsAgo, $startOfTwoMonthsAgo])->sum('price');
        $revenueFourMonthsAgo = DB::table('orders')->whereBetween('created_at', [$startOfFourMonthsAgo, $startOfThreeMonthsAgo])->sum('price');
        $revenueFiveMonthsAgo = DB::table('orders')->whereBetween('created_at', [$startOfFiveMonthsAgo, $startOfFourMonthsAgo])->sum('price');
        $data = [
            'ordersToday' => $ordersToday,
            'ordersThisWeek' => $ordersThisWeek,
            'ordersThisMonth' => $ordersThisMonth,
            'revenueToday' => $revenueToday,
            'revenueThisWeek' => $revenueThisWeek,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueTwoMonthsAgo' => $revenueTwoMonthsAgo,
            'revenueThreeMonthsAgo' => $revenueThreeMonthsAgo,
            'revenueFourMonthsAgo' => $revenueFourMonthsAgo,
            'revenueFiveMonthsAgo' => $revenueFiveMonthsAgo,
        ];

        return response()->json($data);
    }
    public function index()
    {
        $orders = Order::with([
            'user:id,full_name,email',
            'package:id,name,image',
        ])->latest()->get();

        return response()->json($orders);
    }
}
