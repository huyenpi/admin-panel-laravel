@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm người dùng
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/user/store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="full_name">Họ và tên</label>
                        <input class="form-control" type="text" name="full_name" value="{{ old('full_name') }}"
                            id="full_name">
                        @error('full_name')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="username">Tên người dùng</label>
                        <input class="form-control" type="text" name="username" value="{{ old('username') }}"
                            id="username">
                        @error('username')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" name="email" value="{{ old('email') }}"
                            id="email">
                        @error('email')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input class="form-control" type="password" name="password" id="password">
                        @error('password')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Xác nhận mật khẩu</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password-confirm">
                        @error('password_confirmation')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    @php
                        $role_array = [
                            'admin' => 'Quản trị viên',
                            'post editor' => 'Quản lý bài viết',
                            'product manager' => 'Quản lý sản phẩm',
                        ];
                    @endphp

                    <div class="form-group">
                        <label for="">Chức danh</label>
                        <select class="form-control" name="role">
                            @if (!empty($roles))
                                <option value="">Chọn quyền</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role_array[$role->name] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" name="btn-add">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
