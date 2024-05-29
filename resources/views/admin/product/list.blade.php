@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status') == 'success')
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('status') == 'fail')
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách sản phẩm</h5>
                <div class="form-search form-inline">
                    <form action="">
                        <input type="text" name="keyword" value="{{ $keyword }}" class="form-control form-search"
                            placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'in_stock']) }}"
                        class="{{ request()->input('status') == 'in_stock' ? 'text-primary' : 'text-secondary' }}">Còn hàng
                        <span>({{ $count['in_stock'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'out_of_stock']) }}"
                        class="{{ request()->input('status') == 'out_of_stock' ? 'text-primary' : 'text-secondary' }}">Hết
                        hàng
                        <span>({{ $count['out_of_stock'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}"
                        class="{{ request()->input('status') == 'trash' ? 'text-primary' : 'text-secondary' }}">Thùng rác
                        <span>({{ $count['trash'] }})</span></a>
                </div>
                <form action="{{ route('product.multiple_actions') }}">
                    <div class="form-action form-inline py-3">

                        <select name="action" id="action_select" class="form-control mr-1" id="">
                            <option value="">Chọn</option>
                            @if (!empty($actions))
                                @foreach ($actions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            @endif
                        </select>
                        <input type="submit" id="btn_submit_action" name="btn-search" value="Áp dụng"
                            class="btn btn-primary">

                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Giá(VND)</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Thương hiệu</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($products->total() > 0)
                                @foreach ($products as $product)
                                    <tr class="">
                                        <td>
                                            <input type="checkbox" class="can_check" name='ids[]'
                                                value='{{ $product->id }}'>
                                        </td>
                                        <td>{{ $products->firstItem() + $loop->index }}</td>
                                        <td><img style="width:50px;height:auto" src="{{ url($product->image->url) }}"
                                                alt="">
                                        </td>
                                        <td><a href="#">{{ $product->name }}</a></td>
                                        <td>{{ number_format($product->price, 0, '', '.') }}</td>
                                        <td>{{ number_format($product->stock_quantity) }}</td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>{{ $product->brand->name }}</td>
                                        <td>{{ $product->created_at }}</td>

                                        <td>
                                            <a href="{{ route('product.edit', $product->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="{{ request()->input('status') == 'trash' ? route('product.force_delete_one', $product->id) : route('product.delete_one', $product->id) }}"
                                                class="btn btn-danger btn-sm rounded-0 text-white delete_one_link"
                                                type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class='bg-white'>Không tìm thấy bản ghi</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </form>
                {{ $products->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
