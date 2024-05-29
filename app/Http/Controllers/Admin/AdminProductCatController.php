<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminProductCatController extends Controller
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
        session(['sub_module_active' => 'product.category']);
        $product_cats = ProductCategory::paginate(10);
        return view('admin.product_cat.list', compact('product_cats'));
    }

    function store(Request $request)
    {
        $rules = [
            'name' => "required|string|max:60",
        ];
        if ($request->filled('slug')) {
            $rules['slug'] = "required|string|max:60";
        }
        $request->validate(
            $rules,
            [],
            ['name' => 'Tên danh mục']
        );
        $data = [
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => '',
        ];
        if ($request->filled('slug')) {
            $data['slug'] = $request->input('slug');
        }
        if ($request->filled('description')) {
            $data['description'] = $request->input('description');
        }
        if ($request->filled('cat_parent')) {
            $data['parent_id'] = $request->input('cat_parent');
        }
        if ($request->filled('status')) {
            $data['status'] = $request->input('status');
        }

        $data['user_id'] = Auth::id();
        ProductCategory::create($data);
        return redirect()->route('product_cat.list')->with(['status' => 'success', 'message' => 'Đã thêm danh mục thành công']);

    }
    function edit($id)
    {
        $editing_cat = ProductCategory::find($id);
        $product_cats = ProductCategory::paginate(10);
        return view("admin.product_cat.edit", compact("editing_cat", "product_cats"));
    }
    function update(Request $request, $id)
    {

        $rules = [
            'name' => "required|string|max:60",
        ];
        if ($request->filled('slug')) {
            $rules['slug'] = "required|string|max:60";
        }
        $request->validate(
            $rules,
            [],
            ['name' => 'Tên danh mục']
        );
        $product_cat = ProductCategory::find($id);

        $product_cat['name'] = $request->input('name');
        $product_cat['slug'] = Str::slug($request->input('name'));

        if ($request->filled('slug')) {
            $product_cat['slug'] = $request->input('slug');
        }
        if ($request->filled('description')) {
            $product_cat['description'] = $request->input('description');
        }
        if ($request->filled('cat_parent')) {
            $product_cat['parent_id'] = $request->input('cat_parent');
        }
        if ($request->filled('status')) {
            $product_cat['status'] = $request->input('status');
        }
        $product_cat->save();
        return redirect()->route('product_cat.list')->with(['status' => 'success', 'message' => 'Đã cập nhật danh mục thành công']);
    }
}
