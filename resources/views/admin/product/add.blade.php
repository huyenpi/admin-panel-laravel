@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm sản phẩm
            </div>
            <div class="card-body">
                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Giá</label>
                                <input class="form-control" type="number" min='0' name="price" id="name"
                                    value='{{ old('price') }}'>
                                @error('price')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Danh mục</label>
                                <select class="form-control" id="" name="cat">
                                    <option value="">Chọn danh mục</option>
                                    @if (!empty($cats))
                                        @foreach ($cats as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('cat') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('cat')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Thương hiệu</label>
                                <select class="form-control" id="" name="brand">
                                    <option value="">Chọn thương hiệu</option>
                                    @if (!empty($brands))
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('brand')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="quantity">Số lượng</label>
                                <input class="form-control" type="number" min="0" name="quantity" id="quantity"
                                    value="{{ old('quantity') }}">
                                @error('quantity')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input class="form-control" type="text" name="slug" id="slug"
                                    value="{{ old('slug') }}">
                                @error('slug')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="intro">Mô tả sản phẩm</label>
                                <textarea name="description" class="form-control" id="intro" cols="30" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class='text-danger'>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6">
                                    <label for="thumb">Hình ảnh</label>
                                    <div class="input-group">
                                        <input type="file" id="imageInput" class="form-control-file" name="thumb">
                                    </div>
                                    <div id="image_error">

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="imagePreview">
                                        <input type="hidden" id="image_url"name="image_url"
                                            value="{{ old('image_url', url($temp_image->url)) }}">

                                        <img src="{{ old('image_url', url($temp_image->url)) }}"
                                            style="width:80px;height:auto;"alt="Uploaded Image">'

                                        <input type="hidden" id="image_id"name="image_id"
                                            value="{{ old('image_id', $temp_image->id) }}">

                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                        {{ old('featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured">Is Featured</label>
                                    @error('featured')
                                        <small class='text-danger'>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_detail">Chi tiết sản phẩm</label>
                        <textarea name="detail" class="form-control" id="product_detail" cols="30" rows="5">{!! old('detail') !!}</textarea>
                        @error('detail')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
