<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }
    function show()
    {
        $count = [
            'placed' => Order::where([['status', '=', 'placed']])->count(),
            'shipped' => Order::where([['status', '=', 'shipped']])->count(),
            'delivered' => Order::where([['status', '=', 'delivered']])->count(),
            'canceled' => Order::where([['status', '=', 'canceled']])->count(),
            'trash' => Order::onlyTrashed()->count()
        ];
        $sales = Order::where('status', '=', 'delivered')->sum('total_amount');
        $order_items = OrderItem::orderBy('created_at', 'DESC')->paginate(5);
        return view("admin.dashboard", compact('count', 'sales', 'order_items'));
    }
}
