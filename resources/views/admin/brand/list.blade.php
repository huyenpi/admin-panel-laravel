@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm thương hiệu
                    </div>
                    <div class="card-body">
                        <form action="{{ route('brand.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên thương hiệu</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="">Trạng thái</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="exampleRadios1"
                                        value="Chờ duyệt" {{ old('status') == 'Chờ duyệt' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="exampleRadios1">
                                        Chờ duyệt
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="exampleRadios2"
                                        value="Công khai" {{ old('status') == 'Công khai' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="exampleRadios2">
                                        Công khai
                                    </label>
                                </div>
                            </div>



                            <button type="submit" class="btn btn-primary" name="btn_add" value="add_brand">Thêm
                                mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
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
                    <div class="card-header font-weight-bold">
                        Danh sách thương hiệu
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($brands->total() > 0)
                                    @foreach ($brands as $brand)
                                        <tr>
                                            <th scope="row">{{ $brands->firstItem() + $loop->index }}</th>
                                            <td>{{ $brand->name }}</td>
                                            <td>{{ $brand->status }}</td>
                                            <td>
                                                <a href="{{ route('brand.edit', $brand->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $brands->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
