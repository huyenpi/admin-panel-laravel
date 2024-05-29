<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class AdminBrandController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }
    function list()
    {
        session(['sub_module_active' => 'product.brand']);
        $brands = Brand::paginate(10);
        return view('admin.brand.list', compact('brands'));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'name' => "required|string|max:20"
            ],
            [],
            ['name' => 'Tên thương hiệu']
        );
        $data = [
            'name' => $request->input('name')
        ];

        if ($request->filled('status')) {
            $data['status'] = $request->input('status');
        }

        Brand::create($data);
        return redirect()->route('brand.list')->with(['status' => 'success', 'message' => 'Đã thêm thương hiệu thành công']);

    }
    function edit($id)
    {
        $editing_brand = Brand::find($id);
        $brands = Brand::paginate(10);
        return view("admin.brand.edit", compact("editing_brand", "brands"));
    }
    function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255',], [], ['name' => 'Tên thương hiệu']);
        $brand = Brand::find($id);
        $brand['name'] = $request->input('name');

        if ($request->filled('status')) {
            $brand['status'] = $request->input('status');
        }
        $brand->save();
        return redirect()->route('brand.list')->with(['status' => 'success', 'message' => 'Đã cập nhật thương hiệu thành công']);
    }
}
