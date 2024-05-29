<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    function list(Request $request)
    {
        session(['sub_module_active' => 'order.list']);
        $code_to_search = '';
        $status = '';
        if ($request->filled('code_to_search')) {
            $code_to_search = $request->input('code_to_search');
        }
        if ($request->input('status')) {
            $status = $request->input('status');
        }
        $orders = Order::where([['code', 'LIKE', "%{$code_to_search}%"], ['status', 'LIKE', "%{$status}%"]])->orderBy('created_at', 'desc')->paginate(5);
        if ($status == 'trash') {
            $orders = Order::onlyTrashed()->where('code', 'LIKE', "%{$code_to_search}%")->orderBy('created_at', 'desc')->paginate(5);
        }
        $count = [
            'placed' => Order::where([['status', '=', 'placed']])->count(),
            'shipped' => Order::where([['status', '=', 'shipped']])->count(),
            'delivered' => Order::where([['status', '=', 'delivered']])->count(),
            'canceled' => Order::where([['status', '=', 'canceled']])->count(),
            'trash' => Order::onlyTrashed()->count(),
        ];
        return view('admin.order.list', compact('orders', 'count', 'code_to_search'));
    }
    function detail($order_id)
    {
        $order = Order::find($order_id);
        $order_items = OrderItem::where('order_id', '=', $order_id)->get();


        return view('admin.order.detail', compact('order_items', 'order'));

    }
    function deleteOne($id)
    {
        if (Order::where('id', '=', $id)->delete()) {
            return redirect()->route('order.list')->with(['status' => 'success', 'message' => 'Đã xóa nội dung thành công']);
        } else {
            return redirect()->route('order.list')->with(['status' => 'fail', 'message' => 'Xóa không thành công']);
        }
    }
    function forceDeleteOne($id)
    {
        if (Order::onlyTrashed()->where('id', '=', $id)->forceDelete()) {
            return redirect()->route('order.list')->with(['status' => 'success', 'message' => 'Đã xóa nội dung thành công']);
        } else {
            return redirect()->route('order.list')->with(['status' => 'fail', 'message' => 'Xóa không thành công']);
        }
    }
    function edit($id)
    {
        $order = Order::find($id);
        return view('admin.order.edit', compact('order'));
    }
    function update(Request $request, $id)
    {
        $rules = [
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:15',
            'client_address' => 'required|string|max:255',
            'status' => 'required',
            'payment_method' => 'required'
        ];
        $request->validate(
            $rules,
            [],
            [
                'client_name' => 'Tên khách hàng',
                'client_phone' => 'Số điện thoại',
                'client_address' => 'Địa chỉ nhận hàng',
                'status' => 'Trạng thái đơn hàng',
                'payment_method' => 'Phương thức thanh toán'
            ]
        );
        $data = [
            'customer_name' => $request->input('client_name'),
            'shipping_phone' => $request->input('client_phone'),
            'shipping_address' => $request->input('client_address'),
            'status' => $request->input('status'),
            'payment_method' => $request->input('payment_method'),
        ];
        if (Order::where('id', '=', $id)->update($data)) {
            return redirect()->route('order.list')->with(['status' => 'success', 'message' => 'Cập nhật thông tin thành công!']);
        } else {
            return redirect()->route('order.list')->with(['status' => 'fail', 'message' => 'Cập nhật không thành công!']);
        }
    }

    function deleteMany(Request $request)
    {
        $ids = $request->ids;
        if (Order::whereIn('id', $ids)->delete()) {
            return redirect()->route('order.list')->with(['status' => 'success', 'message' => 'Đã xóa nội dung thành công']);
        } else {
            return redirect()->route('order.list')->with(['status' => 'fail', 'message' => 'Xóa không thành công']);
        }
    }
    function forceDeleteMany(Request $request)
    {
        $ids = $request->ids;
        if (Order::whereIn('id', $ids)->forceDelete()) {
            return redirect()->route('order.list')->with(['status' => 'success', 'message' => 'Đã xóa nội dung thành công']);
        } else {
            return redirect()->route('order.list')->with(['status' => 'fail', 'message' => 'Xóa không thành công']);
        }
    }
    function restore(Request $request)
    {
        $ids = $request->ids;
        if (Order::whereIn('id', $ids)->restore()) {
            return redirect()->route('order.list')->with(['status' => 'success', 'message' => 'Đã khôi phục nội dung thành công']);
        } else {
            return redirect()->route('order.list')->with(['status' => 'fail', 'message' => 'Khôi phục không thành công']);
        }
    }
    function multipleActions(Request $request)
    {
        $ids = $request->ids;
        $select_action = $request->input('select_action');

        if (empty($select_action)) {
            return redirect()->route('order.list')->with(['status' => 'fail', 'message' => 'Chưa chọn hành động.']);
        }
        if (empty($ids)) {
            return redirect()->route('order.list')->with(['status' => 'fail', 'message' => 'Chưa chọn nội dung']);
        }

        if ($select_action == 'delete') {
            return redirect()->route('order.delete_many', compact('ids'));
        } elseif ($select_action == 'force_delete') {
            return redirect()->route('order.force_delete_many', compact('ids'));
        } elseif ($select_action == 'restore') {
            return redirect()->route('order.restore', compact('ids'));
        }
    }
    function updateStatus(Request $request, $id)
    {
        if (Order::where('id', '=', $id)->update(['status' => $request->input('status')])) {
            return redirect()->route('order.detail', $id)->with(['status' => 'success', 'message' => 'Cập nhật trạng thái đơn hàng thành công']);
        }
        ;
        return redirect()->route('order.detail', $id)->with(['status' => 'fail', 'message' => 'Cập nhật trạng thái đơn hàng thất bại']);

    }
}
