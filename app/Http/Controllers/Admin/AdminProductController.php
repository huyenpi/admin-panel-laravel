<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Image;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        session(['sub_module_active' => 'product.list']);

        $keyword = "";
        if ($request->has('btn-search')) {
            $keyword = $request->input('keyword');
        }

        $products = Product::where('name', 'like', "%{$keyword}%")->paginate(5);

        if ($request->input('status') == 'in_stock') {
            $products = Product::where([
                ['stock_quantity', '>', 0],
                ['name', 'like', "%{$keyword}%"]
            ])->paginate(5);

            //Xử lí Nếu current page lớn hơn last page, set current page bằng last page
            if ($request->input('page') > $products->lastPage()) {
                $request->merge(['page' => $products->lastPage()]);
                $products = Product::where([
                    ['stock_quantity', '>', 0],
                    ['name', 'like', "%{$keyword}%"]
                ])->paginate(5);

            }

        } else if ($request->input('status') == 'out_of_stock') {
            $products = Product::where([
                ['stock_quantity', '=', 0],
                ['name', 'like', "%{$keyword}%"]
            ])->paginate(5);
            if ($request->input('page') > $products->lastPage()) {
                $request->merge(['page' => $products->lastPage()]);
                $products = Product::where([
                    ['stock_quantity', '=', 0],
                    ['name', 'like', "%{$keyword}%"]
                ])->paginate(5);

            }
        } else if ($request->input('status') == 'trash') {
            $products = Product::onlyTrashed()->where('name', 'like', "%{$keyword}%")->paginate(5);
            if ($request->input('page') > $products->lastPage()) {
                $request->merge(['page' => $products->lastPage()]);
                $products = Product::onlyTrashed()->where('name', 'like', "%{$keyword}%")->paginate(5);
            }
        }

        $count = [
            'in_stock' => Product::where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => Product::where(
                'stock_quantity',
                '=',
                0
            )->count(),
            'trash' => Product::onlyTrashed()->count(),
        ];

        $actions = [
            'delete' => 'Xóa tạm thời'
        ];
        if ($request->input('status') == 'trash') {
            $actions = [
                'restore' => 'Khôi phục',
                'force_delete_many' => 'Xóa vĩnh viễn'
            ];
        }

        return view('admin.product.list', compact('products', 'count', 'keyword', 'actions'));
    }
    function add()
    {
        session(['sub_module_active' => 'product.add']);
        $cats = ProductCategory::all();
        $brands = Brand::all();
        $temp_image = Image::find(1);
        return view('admin.product.add', compact('cats', 'brands', 'temp_image'));
    }
    function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:120',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'cat' => 'required',
            'brand' => 'required',
            'description' => 'required|string',
            'detail' => 'required|string',
        ];
        if ($request->filled('slug')) {
            $rules['slug'] = 'required|string|max:120|regex:/^\S*$/';
        }

        $request->validate(
            $rules,
            [],
            [
                'name' => 'Tên',
                'price' => 'Giá',
                'quantity' => 'Số lượng',
                'thumb' => 'Hình ảnh',
                'cat' => 'Danh mục',
                'brand' => 'Thương hiệu',
                'description' => 'Mô tả',
                'detail' => 'Chi tiết'
            ]
        );
        $data = [
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'stock_quantity' => $request->input('quantity'),
            'category_id' => $request->input('cat'),
            'brand_id' => $request->input('brand'),
            'description' => $request->input('description'),
            'details' => $request->input('detail'),
            'image_id' => $request->input('image_id'),
            'user_id' => Auth::id(),
            'slug' => Str::slug($request->input('name'))
        ];
        if ($request->filled('slug')) {
            $data['slug'] = $request->input('slug');
        }
        if ($request->filled('featured')) {
            $data['is_featured'] = 1;
        }
        Product::create($data);
        return redirect()->route('product.list')->with(['status' => 'success', 'message' => 'Thêm sản phẩm thành công!']);
    }

    function edit($id)
    {
        $product = Product::find($id);
        $cats = ProductCategory::all();
        $brands = Brand::all();
        return view('admin.product.edit', compact('product', 'cats', 'brands'));
    }
    function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'cat' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'detail' => 'required|string',
        ];
        if ($request->filled('slug')) {
            $rules['slug'] = 'required|string|max:120|regex:/^\S*$/';
        }
        $request->validate(
            $rules,
            [],
            [
                'name' => 'Tên',
                'price' => 'Giá',
                'quantity' => 'Số lượng',
                'thumb' => 'Hình ảnh',
                'cat' => 'Danh mục',
                'brand' => 'Thương hiệu',
                'description' => 'Mô tả',
                'detail' => 'Chi tiết'
            ]
        );
        $data = [
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'stock_quantity' => $request->input('quantity'),
            'category_id' => $request->input('cat'),
            'brand_id' => $request->input('brand'),
            'description' => $request->input('description'),
            'details' => $request->input('detail'),
            'image_id' => $request->input('image_id'),
            'slug' => Str::slug($request->input('name'))

        ];
        if ($request->filled('slug')) {
            $data['slug'] = $request->input('slug');
        }
        if ($request->filled('featured')) {
            $data['is_featured'] = 1;
        } else {
            $data['is_featured'] = 0;
        }
        Product::where('id', '=', $id)->update($data);
        return redirect()->route('product.list')->with(['status' => 'success', 'message' => 'Cập nhật sản phẩm thành công!']);
    }
    function deleteOne($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('product.list')->with(['status' => 'success', 'message' => 'Đã xóa nội dung thành công!']);
    }
    function deleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (Product::whereIn('id', $ids)->delete()) {
            return redirect()->route('product.list')->with(['status' => 'success', 'message' => 'Đã xóa nội dung thành công!']);
        } else {
            return redirect()->route('product.list')->with(['status' => 'fail', 'message' => 'Xóa thất bại!']);
        }
        ;
    }
    function forceDeleteOne($id)
    {
        if (Product::onlyTrashed()->where('id', '=', $id)->forceDelete()) {
            return redirect()->route('product.list')->with(['status' => 'success', 'message' => 'Đã xóa nội dung thành công!']);
        } else {
            return redirect()->route('product.list')->with(['status' => 'fail', 'message' => 'Xóa thất bại!']);
        }
    }
    function forceDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (Product::onlyTrashed()->whereIn('id', $ids)->forceDelete()) {
            return redirect()->route('product.list')->with(['status' => 'success', 'message' => 'Đã xóa nội dung thành công!']);
        } else {
            return redirect()->route('product.list')->with(['status' => 'fail', 'message' => 'Xóa thất bại!']);
        }
    }
    function restore(Request $request)
    {
        $ids = $request->ids;
        if (Product::onlyTrashed()->whereIn('id', $ids)->restore()) {
            return redirect()->route('product.list')->with(['status' => 'success', 'message' => 'Đã khôi phục nội dung thành công!']);
        } else {
            return redirect()->route('product.list')->with(['status' => 'fail', 'message' => 'Khôi phục thất bại!']);
        }
        ;
    }
    function multipleActions(Request $request)
    {

        $action = $request->input('action');
        $ids = $request->input('ids');
        if (empty($ids)) {
            return redirect()->route('product.list')->with(['status' => 'fail', 'message' => 'Chưa chọn nội dung!']);
        }
        if ($action == null) {
            return redirect()->route('product.list')->with(['status' => 'fail', 'message' => 'Chưa chọn hàng động!']);
        } elseif ($action == 'delete') {
            return redirect()->route('product.delete_many', ['ids' => $ids]);
        } elseif ($action == 'restore') {
            return redirect()->route('product.restore', ['ids' => $ids]);
        } elseif ($action == 'force_delete_many') {
            return redirect()->route('product.force_delete_many', ['ids' => $ids]);
        }

    }


}
