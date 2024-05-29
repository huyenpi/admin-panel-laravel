@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            {{-- Thông báo nếu xử lí cập nhật thông tin --}}
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
                <h5 class="m-0 ">Chi tiết đơn hàng <span class='text-muted'>#{{ !empty($order) ? $order->code : '' }}</span>
                </h5>
            </div>
            <div class="card-body">
                {{-- Hiển thị thông tin khách hàng --}}
                @if ($order)
                    <div class="row">
                        <div class="col col-6">
                            <table class="table table-bordered table-hover table-sm">
                                <tr>
                                    <td class="font-italic">Tên khách hàng</td>
                                    <td>{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-italic">Số điện thoại</td>
                                    <td> {{ $order->shipping_phone }}</td>
                                </tr>
                                <tr>
                                    <td class="font-italic">Địa chỉ nhận hàng</td>
                                    <td>{{ $order->shipping_address }}</td>
                                </tr>
                                <tr>
                                    <td class="font-italic">Phương thức thanh toán</td>
                                    <td>{{ $order->payment_method }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col col-4">
                            @php
                                $status = [
                                    'placed' => 'Đã đặt hàng',
                                    'shipped' => 'Đang giao hàng',
                                    'delivered' => 'Đã giao hàng',
                                    'canceled' => 'Đã hủy',
                                ];
                            @endphp
                            <form action="{{ route('order.update_status', $order->id) }}" method="POST">
                                @csrf
                                <label for="status" class="font-italic">Trạng thái đơn hàng: </label>
                                <div class="input-group">
                                    <select name="status" id="status" class="form-select">
                                        @foreach ($status as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ $order->status == $key ? 'selected' : '' }}> {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="submit" class="btn btn-sm btn-primary input-group-append"
                                        name="btn-update-status" value="Cập nhật">
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                {{-- Hiển thị thông tin các sản phẩm trong đơn hàng --}}
                <table class="table table-striped table-hover table-checkall">
                    <thead>
                        <tr>

                            <th scope="col">#</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Thành tiền(Vnd)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($order_items[0]))
                            @foreach ($order_items as $order_item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <img src="{{ url($order_item->product->image->url) }}"
                                            style="width:50px;height:auto;" alt="">
                                    </td>
                                    <td>
                                        {{ $order_item->product->name }}
                                    </td>
                                    <td>{{ number_format($order_item->price, 0, '', '.') }}</td>
                                    <td>{{ $order_item->quantity }}</td>
                                    <td>
                                        {{ number_format($order_item->quantity * $order_item->price, 0, '', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="bg-white">Không tìm thấy bản ghi.</td>
                            </tr>
                        @endif
                        @if (!empty($order))
                            <tr class="text-success">
                                <td colspan="4"></td>
                                <td colspan="" class='font-weight-bold'>
                                    {{ $order->product_quantity }}
                                </td>
                                <td colspan="" class='font-weight-bold'>
                                    {{ number_format($order->total_amount, 0, '', '.') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
