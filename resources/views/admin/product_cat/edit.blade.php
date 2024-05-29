@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Cập nhật danh mục
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product_cat.update', $editing_cat->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', $editing_cat->name) }}">
                                @error('name')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Mô tả danh mục</label>
                                <input class="form-control" type="text" name="description" id="description"
                                    value="{{ old('description', $editing_cat->description) }}">
                                @error('description')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="slug">slug</label>
                                <input class="form-control" type="text" name="slug" id="slug"
                                    value="{{ old('slug', $editing_cat->slug) }}">
                                @error('slug')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Danh mục cha</label>
                                <select class="form-control" name="cat_parent" id="">
                                    <option value="">Chọn danh mục</option>
                                    @if (!empty($product_cats))
                                        @foreach ($product_cats as $product_cat)
                                            @if ($editing_cat->id != $product_cat->id)
                                                <option value="{{ $product_cat->id }}"
                                                    {{ old('cat_parent', $editing_cat->parent_id) == $product_cat->id ? 'selected' : '' }}>
                                                    {{ $product_cat->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Trạng thái</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="exampleRadios1"
                                        value="Chờ duyệt"
                                        {{ old('status', $editing_cat) == 'Chờ duyệt' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="exampleRadios1">
                                        Chờ duyệt
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="exampleRadios2"
                                        value="Công khai"
                                        {{ old('status', $editing_cat) == 'Công khai' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="exampleRadios2">
                                        Công khai
                                    </label>
                                </div>
                            </div>



                            <button type="submit" class="btn btn-primary" name="btn_update" value="update_product_cat">Cập
                                nhật</button>
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
                        Danh sách danh mục
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Danh mục cha</th>
                                    <th scope="col">Mô tả</th>
                                    <th scope="col">Người tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($product_cats->total() > 0)
                                    @foreach ($product_cats as $product_cat)
                                        <tr>
                                            <th scope="row">{{ $product_cats->firstItem() + $loop->index }}</th>
                                            <td>{{ $product_cat->name }}</td>
                                            <td>{{ $product_cat->slug }}</td>
                                            <td>{{ !empty($product_cat->parent->name) ? $product_cat->parent->name : 'Không có' }}
                                            </td>
                                            <td>{{ $product_cat->description }}</td>
                                            <td>{{ $product_cat->user->full_name }}</td>
                                            <td>
                                                <a href="{{ route('product_cat.edit', $product_cat->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $product_cats->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
