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
                <h5 class="m-0 ">Danh sách đơn hàng</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" name="code_to_search" value="{{ $code_to_search }}"
                            class="form-control form-search" placeholder="Nhập mã đơn hàng">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'placed']) }}"
                        class=" {{ request()->input('status') == 'placed' ? 'text-primary' : 'text-muted' }}">Đã
                        đặt<span>({{ $count['placed'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'shipped']) }}"
                        class="{{ request()->input('status') == 'shipped' ? 'text-primary' : 'text-muted' }}">Đang giao
                        <span>({{ $count['shipped'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'delivered']) }}"
                        class="{{ request()->input('status') == 'delivered' ? 'text-primary' : 'text-muted' }}">Đã giao
                        <span>({{ $count['delivered'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'canceled']) }}"
                        class="{{ request()->input('status') == 'canceled' ? 'text-primary' : 'text-muted' }}">Đã
                        hủy<span>({{ $count['canceled'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}"
                        class="{{ request()->input('status') == 'trash' ? 'text-primary' : 'text-muted' }}">Thùng
                        rác<span>({{ $count['trash'] }})</span></a>
                </div>
                <form action="{{ route('order.multiple_actions') }}">
                    <div class="form-action form-inline py-3">
                        @php
                            $actions = [
                                'delete' => 'Xóa tạm thời',
                            ];
                            if (request()->input('status') == 'trash') {
                                $actions = [
                                    'restore' => 'Khôi phục',
                                    'force_delete' => 'Xóa vĩnh viễn',
                                ];
                            }
                        @endphp
                        <select name="select_action" class="form-control mr-1" id="action_select">
                            <option value=''>Chọn</option>
                            @foreach ($actions as $key => $value)
                                <option value='{{ $key }}'>{{ $value }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn_submit_action" id="btn_submit_action" value="Áp dụng"
                            class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Mã đơn hàng</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Tổng tiền(Vnd)</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Thời gian đặt hàng</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($orders->total() > 0)
                                @php
                                    $status = [
                                        'placed' => 'Đã đặt hàng',
                                        'shipped' => 'Đang giao hàng',
                                        'delivered' => 'Đã giao hàng',
                                        'canceled' => 'Đã hủy',
                                    ];
                                @endphp
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $order->id }}"
                                                class="can_check">
                                        </td>
                                        <td>{{ $orders->firstItem() + $loop->index }}</td>
                                        <td><a href="{{ route('order.detail', $order->id) }}">{{ $order->code }}</a></td>
                                        <td>
                                            {{ $order->customer_name }}<br>
                                            {{ $order->shipping_phone }}

                                        </td>
                                        <td>{{ $order->product_quantity }}</td>
                                        <td>{{ number_format($order->total_amount, 0, '', '.') }}</td>
                                        <td><span
                                                class="badge {{ $order->status == 'placed' ? 'badge-primary' : '' }} {{ $order->status == 'delivered' ? 'badge-success' : '' }} {{ $order->status == 'shipped' ? 'badge-warning' : '' }} {{ $order->status == 'canceled' ? 'badge-muted' : '' }}">{{ $status[$order->status] }}</span>
                                        </td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>
                                            @if ($order->status == 'placed')
                                                <a href="{{ route('order.edit', $order->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                            @else
                                                <a href="#" style="pointer-events: none"
                                                    class="btn btn-secondary btn-sm rounded-0 text-white disabled_link"
                                                    type="button" data-toggle="tooltip" data-placement="top"
                                                    title="Edit"><i class="fa fa-edit"></i></a>
                                            @endif

                                            <a href="{{ route(request()->input('status') == 'trash' ? 'order.force_delete_one' : 'order.delete_one', $order->id) }}"
                                                class="btn btn-danger btn-sm rounded-0 text-white delete_one_link"
                                                type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="bg-white">Không tìm thấy bản ghi.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $orders->withQueryString(['status' => request()->input('status'), 'code_to_search' => request()->input('code_to_search')])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
