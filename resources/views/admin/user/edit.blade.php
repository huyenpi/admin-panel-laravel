@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật thông tin người dùng
            </div>
            <div class="card-body">
                <form action="{{ url("/admin/user/update/{$user->id}") }}" method="POST">
                    @csrf
                    <div class="form-group">

                        <label for="full_name">Họ và tên</label>
                        <input class="form-control" type="text" name="full_name"
                            value="{{ old('full_name', $user->full_name) }}" id="full_name">
                        @error('full_name')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">

                        <label for="username">Tên người dùng</label>
                        <input class="form-control" type="text" name="username"
                            value="{{ old('username', $user->username) }}" id="username" disabled>
                        @error('username')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">

                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}"
                            id="email" disabled>
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

                    <div class="form-group">
                        @php
                            $role_array = [
                                'admin' => 'Quản trị viên',
                                'post editor' => 'Quản lý bài viết',
                                'product manager' => 'Quản lý sản phẩm',
                            ];
                        @endphp
                        <label for="">Chức danh</label>
                        <select class="form-control" name="role">
                            @if (!empty($roles))
                                <option value="">Chọn quyền</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role', $user->role !== null ? $user->role->id : null) == $role->id ? 'selected' : '' }}>
                                        {{ $role_array[$role->name] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select class="form-control" name="status" id='status'>
                            <option value="">Chọn trạng thái</option>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                active</option>
                            <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>
                                banned</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" name="btn-edit">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
