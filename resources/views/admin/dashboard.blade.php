@extends('layouts.admin')
@section('content')
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $count['delivered'] }}</h5>
                        <p class="card-text">Đơn hàng giao dịch thành công</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐANG XỬ LÝ</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $count['placed'] + $count['shipped'] }}</h5>
                        <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">DOANH SỐ</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ number_format($sales, 0, '', '.') }} VND</h5>
                        <p class="card-text">Doanh số hệ thống</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN HÀNG HỦY</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $count['canceled'] }}</h5>
                        <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end analytic  -->
        <div class="card">
            <div class="card-header font-weight-bold">
                ĐƠN HÀNG MỚI
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mã</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Thành tiền(VND)</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày đặt hàng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $status = [
                                'placed' => 'Đã đặt hàng',
                                'shipped' => 'Đang giao hàng',
                                'delivered' => 'Đã giao hàng',
                                'canceled' => 'Đã hủy',
                            ];
                        @endphp
                        @if ($order_items->total() > 0)
                            @foreach ($order_items as $order_item)
                                <tr>
                                    <th scope="row">{{ $order_items->firstItem() + $loop->index }}</th>
                                    <td><a
                                            href="{{ route('order.detail', $order_item->order->id) }}">{{ $order_item->order->code }}</a>
                                    </td>
                                    <td>
                                        {{ $order_item->order->customer_name }} <br>
                                        {{ $order_item->order->shipping_phone }}
                                    </td>
                                    <td><a href="#">{{ $order_item->product->name }}</a></td>
                                    <td><img src="{{ url($order_item->product->image->url) }}"
                                            style="width:50px;height:auto" /></td>
                                    <td>{{ $order_item->quantity }}</td>
                                    <td>{{ number_format($order_item->price * $order_item->quantity, 0, '', '.') }}</td>
                                    <td><span
                                            class="badge {{ $order_item->order->status == 'delivered' ? 'badge-success' : '' }} {{ $order_item->order->status == 'shipped' ? 'badge-warning' : '' }} {{ $order_item->order->status == 'placed' ? 'badge-primary' : '' }} {{ $order_item->order->status == 'canceled' ? 'badge-muted' : '' }}">{{ $status[$order_item->order->status] }}</span>
                                    </td>
                                    <td>{{ $order_item->created_at }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{ $order_items->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
@endsection
