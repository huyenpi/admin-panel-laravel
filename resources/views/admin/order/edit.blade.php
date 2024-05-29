@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật thông tin đơn hàng <span class='text-muted'>#{{ $order->code }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('order.update', $order->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tên khách hàng</label>
                        <input class="form-control" type="text" name="client_name"
                            value="{{ old('client_name', $order->customer_name) }}" id="name">
                        @error('client_name')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input class="form-control" type="text" name="client_phone"
                            value="{{ old('shipping_phone', $order->shipping_phone) }}" id="phone">
                        @error('client_phone')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ nhận hàng</label>
                        <input class="form-control" type="text" name="client_address"
                            value="{{ old('client_address', $order->shipping_address) }}"id="address">
                        @error('client_address')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Phương thức thanh toán</label><br>
                        <input class="ml-5" id="cod" type="radio" name="payment_method" value="COD"
                            {{ old('payment_method', $order->payment_method) == 'COD' ? 'checked' : '' }}>
                        <label for="cod">COD</label><br>
                        <input class="ml-5" id="online" type="radio" name="payment_method" value="Online Payment"
                            {{ old('payment_method', $order->payment_method) == 'Online Payment' ? 'checked' : '' }}>
                        <label for="online">Thanh toán online</label>
                    </div>

                    <div class="form-group">
                        @php
                            $status = [
                                'placed' => 'Đã đặt hàng',
                                'shipped' => 'Đang giao hàng',
                                'delivered' => 'Đã giao hàng',
                                'canceled' => 'Đã hủy',
                            ];
                        @endphp
                        <label for="status">Trạng thái đơn hàng</label>
                        <select name="status" id="status" class="form-control">
                            @foreach ($status as $key => $value)
                                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn-edit">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
