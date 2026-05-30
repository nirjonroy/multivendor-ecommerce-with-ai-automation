@php
    $sizeRows = old('size_prices', $product?->size_prices ?: [['size' => 'free', 'price' => null]]);
    $colorRows = old('color_images', $product?->color_images ?: [['color' => 'default', 'image_path' => null]]);
    $stockRows = old('variation_stocks', $product?->variation_stocks ?: [['variation' => 'free_default', 'quantity' => $product?->stock_quantity ?? null]]);
@endphp

@extends('vendor.layouts.app', ['title' => $product ? 'Edit Product' : 'Create Product'])

@section('page_title', $product ? 'Edit Product' : 'Create Product')

@push('styles')
    <style>
        .preview-img{width:150px;height:150px;object-fit:contain;border:1px solid #ddd;background:#f3f6f7}
        .variation-table th,.variation-table td{vertical-align:middle;background:#fafafa}
        .btn-magenta{background:#b914b5;color:#fff}
        .btn-magenta:hover{color:#fff}
        .product-form-card{background:#fff;padding:24px;border-radius:4px}
    </style>
@endpush

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form class="product-form-card" method="POST" action="{{ $product ? route('vendor.products.update', $product) : route('vendor.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if($product)
            @method('PUT')
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-outline-primary" href="{{ route('vendor.products.index') }}">Back</a>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Thumbnail Image Preview</label><br>
                @if($product?->thumbnail_path)
                    <img class="preview-img" src="{{ asset('storage/' . $product->thumbnail_path) }}" alt="{{ $product->name }}">
                @else
                    <img class="preview-img" src="/assets/images/placeholder.jpg" alt="Preview">
                @endif
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Thumbnail Image *</label>
                <input class="form-control" type="file" name="thumbnail" accept="image/*">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">Upload Images *</label>
                <input class="form-control" type="file" name="gallery[]" accept="image/*" multiple>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-6 mb-3"><label class="form-label">Name *</label><input class="form-control" name="name" value="{{ old('name', $product?->name) }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Video Url</label><input class="form-control" name="video_url" value="{{ old('video_url', $product?->video_url) }}"></div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Category *</label>
                <select class="form-select" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Sub Category</label>
                <select class="form-select" name="sub_category_id">
                    <option value="">Select Sub Category</option>
                    @foreach($subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}" @selected(old('sub_category_id', $product?->sub_category_id) == $subCategory->id)>{{ $subCategory->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Child Category</label>
                <select class="form-select" name="child_category_id">
                    <option value="">Select Child Category</option>
                    @foreach($childCategories as $childCategory)
                        <option value="{{ $childCategory->id }}" @selected(old('child_category_id', $product?->child_category_id) == $childCategory->id)>{{ $childCategory->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Brand</label>
                <select class="form-select" name="brand_id">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">SKU</label><input class="form-control" name="sku" value="{{ old('sku', $product?->sku) }}"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="{{ old('slug', $product?->slug) }}" placeholder="Auto generated if empty"></div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="published" @selected(old('status', $product?->status ?? 'published') === 'published')>Published</option>
                    <option value="draft" @selected(old('status', $product?->status) === 'draft')>Draft</option>
                    <option value="inactive" @selected(old('status', $product?->status) === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Price *</label><input class="form-control" type="number" step="0.01" name="price" value="{{ old('price', $product?->price) }}" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Offer Price</label><input class="form-control" type="number" step="0.01" name="offer_price" value="{{ old('offer_price', $product?->offer_price) }}"></div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">Stock Quantity *</label><input class="form-control" type="number" name="stock_quantity" value="{{ old('stock_quantity', $product?->stock_quantity ?? 0) }}" required></div>
        </div>

        <div class="mb-3"><label class="form-label">Short Description</label><textarea class="form-control" name="short_description" rows="2">{{ old('short_description', $product?->short_description) }}</textarea></div>
        <div class="mb-3"><label class="form-label">Long Description *</label><textarea class="form-control" id="editor1" name="long_description" rows="8">{{ old('long_description', $product?->long_description) }}</textarea></div>

        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">Product Size and price</label><select class="form-select"><option>Variable Size</option></select></div>
            <div class="col-md-4 mb-3"><label class="form-label">Product Color and Image</label><select class="form-select"><option>Variable Color</option></select></div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Variation Stock</label>
                <select class="form-select" name="has_variation_stock">
                    <option value="1" @selected(old('has_variation_stock', $product?->has_variation_stock ?? true))>Yes</option>
                    <option value="0" @selected(! old('has_variation_stock', $product?->has_variation_stock ?? true))>No</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <table class="table variation-table" id="size-table">
                    <thead><tr><th>Size</th><th>Price</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($sizeRows as $i => $row)
                            <tr>
                                <td><select class="form-select" name="size_prices[{{ $i }}][size]">@foreach($productSizes as $size)<option value="{{ $size->name }}" @selected(($row['size'] ?? '') === $size->name)>{{ $size->name }}</option>@endforeach</select></td>
                                <td><input class="form-control" name="size_prices[{{ $i }}][price]" value="{{ $row['price'] ?? '' }}" placeholder="Price"></td>
                                <td><button type="button" class="btn btn-magenta add-size">Add</button> <button type="button" class="btn btn-danger remove-row">Delete</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-7">
                <table class="table variation-table" id="color-table">
                    <thead><tr><th>Color</th><th>Image</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($colorRows as $i => $row)
                            <tr>
                                <td><select class="form-select" name="color_images[{{ $i }}][color]">@foreach($productColors as $color)<option value="{{ $color->name }}" @selected(($row['color'] ?? '') === $color->name)>{{ $color->name }}</option>@endforeach</select></td>
                                <td><input class="form-control" type="file" name="color_images[{{ $i }}][image]" accept="image/*">@if(!empty($row['image_path']))<small>{{ basename($row['image_path']) }}</small>@endif</td>
                                <td><button type="button" class="btn btn-magenta add-color">Add</button> <button type="button" class="btn btn-danger remove-row">Delete</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Manage Variation Quantity</label>
            <table class="table variation-table" id="stock-table">
                <thead><tr><th>SL</th><th>Variation</th><th>Quantity</th></tr></thead>
                <tbody>
                    @foreach($stockRows as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><input class="form-control variation-name" name="variation_stocks[{{ $i }}][variation]" value="{{ $row['variation'] ?? '' }}" readonly></td>
                            <td><input class="form-control variation-quantity" type="number" name="variation_stocks[{{ $i }}][quantity]" value="{{ $row['quantity'] ?? '' }}" placeholder="Quantity"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <label class="d-block mt-3"><input type="checkbox" name="is_new" value="1" @checked(old('is_new', $product?->is_new ?? true))> New</label>
        <label class="d-block"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured))> Featured</label>
        <label class="d-block"><input type="checkbox" name="is_on_sale" value="1" @checked(old('is_on_sale', $product?->is_on_sale))> On Sale</label>
        <div class="mt-4"><button class="btn btn-primary" type="submit">Save</button></div>
    </form>
@endsection

@push('scripts')
    <script src="/assets/js/editor/ckeditor/ckeditor.js"></script>
    <script src="/assets/js/editor/ckeditor/styles.js"></script>
    <script>
        let sizeIndex = {{ count($sizeRows) }};
        let colorIndex = {{ count($colorRows) }};
        const sizeOptions = `{!! $productSizes->map(fn($s) => '<option value="' . e($s->name) . '">' . e($s->name) . '</option>')->implode('') !!}`;
        const colorOptions = `{!! $productColors->map(fn($c) => '<option value="' . e($c->name) . '">' . e($c->name) . '</option>')->implode('') !!}`;

        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('editor1');
        }

        function currentQuantities() {
            const quantities = {};
            $('#stock-table tbody tr').each(function () {
                const variation = $(this).find('.variation-name').val();
                if (variation) quantities[variation] = $(this).find('.variation-quantity').val();
            });
            return quantities;
        }

        function selectedValues(selector, fallback) {
            const values = [];
            $(selector).each(function () {
                const value = $(this).val();
                if (value && !values.includes(value)) values.push(value);
            });
            return values.length ? values : [fallback];
        }

        function rebuildVariationStocks() {
            const quantities = currentQuantities();
            const sizes = selectedValues('#size-table select', 'free');
            const colors = selectedValues('#color-table select', 'default');
            let rows = '';
            let index = 0;
            sizes.forEach(function (size) {
                colors.forEach(function (color) {
                    const variation = `${size}_${color}`;
                    const quantity = quantities[variation] ?? '';
                    rows += `<tr><td>${index + 1}</td><td><input class="form-control variation-name" name="variation_stocks[${index}][variation]" value="${variation}" readonly></td><td><input class="form-control variation-quantity" type="number" name="variation_stocks[${index}][quantity]" value="${quantity}" placeholder="Quantity"></td></tr>`;
                    index++;
                });
            });
            $('#stock-table tbody').html(rows);
        }

        $(document).on('click', '.add-size', function () {
            $('#size-table tbody').append(`<tr><td><select class="form-select" name="size_prices[${sizeIndex}][size]">${sizeOptions}</select></td><td><input class="form-control" name="size_prices[${sizeIndex}][price]" placeholder="Price"></td><td><button type="button" class="btn btn-magenta add-size">Add</button> <button type="button" class="btn btn-danger remove-row">Delete</button></td></tr>`);
            sizeIndex++;
            rebuildVariationStocks();
        });

        $(document).on('click', '.add-color', function () {
            $('#color-table tbody').append(`<tr><td><select class="form-select" name="color_images[${colorIndex}][color]">${colorOptions}</select></td><td><input class="form-control" type="file" name="color_images[${colorIndex}][image]" accept="image/*"></td><td><button type="button" class="btn btn-magenta add-color">Add</button> <button type="button" class="btn btn-danger remove-row">Delete</button></td></tr>`);
            colorIndex++;
            rebuildVariationStocks();
        });

        $(document).on('click', '.remove-row', function () {
            if ($(this).closest('tbody').find('tr').length > 1) {
                $(this).closest('tr').remove();
                rebuildVariationStocks();
            }
        });
        $(document).on('change', '#size-table select,#color-table select', rebuildVariationStocks);
        $(document).ready(rebuildVariationStocks);
    </script>
@endpush
