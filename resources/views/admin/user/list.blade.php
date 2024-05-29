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
                <h5 class="m-0 ">Danh sách thành viên</h5>
                <div class="form-search form-inline">
                    <form action="" method="GET">
                        @csrf
                        <input type="" class="form-control form-search" placeholder="Tìm kiếm" name="keyword"
                            value="{{ request()->input('keyword') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">

                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}"
                        class="{{ request()->input('status') == 'active' ? 'text-primary' : 'text-secondary' }}">Hoạt
                        động<span>({{ $count['active'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'banned']) }}"
                        class="{{ request()->input('status') == 'banned' ? 'text-primary' : 'text-secondary' }}">Vô hiệu hóa
                        <span>({{ $count['banned'] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}"
                        class="{{ request()->input('status') == 'trash' ? 'text-primary' : 'text-secondary' }}">Thùng rác
                        <span>({{ $count['trash'] }})</span></a>
                </div>
                <form name="form_action" action="{{ route('user.multiple_actions') }}">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select name="action" class="form-control mr-1" id="action_select">
                            <option value="">Chọn</option>
                            @if (!empty($actions))
                                @foreach ($actions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            @endif
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
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Lần đăng nhập trước</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($users->total() > 0)
                                @foreach ($users as $key => $user)
                                    <tr>
                                        @if (auth()->id() == $user->id)
                                            <td>
                                                <input type="checkbox" name= "ids[]" value={{ $user->id }}
                                                    @disabled(true)>
                                            </td>
                                        @endif
                                        @if (auth()->id() != $user->id)
                                            <td>
                                                <input type="checkbox" name= "ids[]" value={{ $user->id }}
                                                    class='can_check'>
                                            </td>
                                        @endif

                                        <th scope="row">{{ $users->firstItem() + $loop->index }}</th>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role !== null ? $user->role->name : 'Không có' }}</td>
                                        <td><small
                                                class='badge {{ $user->status == 'active' ? 'badge-success' : 'badge-danger' }}'>
                                                {{ $user->status }}</small>
                                        </td>
                                        <td>{{ $user->created_at !== null ? $user->created_at : 'Không có' }}</td>
                                        <td>{{ $user->last_login_time !== null ? $user->last_login_time : 'Không có' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('user.edit', $user->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            @if (auth()->id() != $user->id)
                                                <a href="@if (request()->input('status') == 'trash') {{ route('user.force_delete_one', $user->id) }} @else {{ route('user.delete_one', $user->id) }} @endif"class="btn btn-danger btn-sm rounded-0 text-white delete_one_link"
                                                    type="button" data-toggle="tooltip" data-placement="top"
                                                    title="Delete"><i class="fa fa-trash"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="bg-white">Không tìm thấy bản ghi

                                </tr>
                            @endif

                        </tbody>
                    </table>
                </form>

                {{ $users->appends(['keyword' => request()->input('keyword'), 'status' => request()->input('status')])->links('pagination::bootstrap-4') }}


                {{-- <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">Trước</span>
                                <span class="sr-only">Sau</span>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav> --}}
            </div>
        </div>
    </div>
@endsection
