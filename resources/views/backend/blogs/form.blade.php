@extends('backend.layouts.app')

@php($isEdit = $blog->exists)

@section('title', $isEdit ? 'Edit Blog' : 'Create Blog')
@section('page_title', $isEdit ? 'Edit Blog' : 'Create Blog')

@section('page_actions')
    <a class="btn btn-outline-primary" href="{{ route('admin.blogs.index') }}">Back</a>
@endsection

@section('content')
    <form method="POST" action="{{ $isEdit ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-xl-2 col-md-3" for="title"><span class="text-danger">*</span> Title</label>
                    <input class="form-control col-xl-9 col-md-8" id="title" name="title" type="text" value="{{ old('title', $blog->title) }}" required>
                </div>

                <div class="form-group row">
                    <label class="col-xl-2 col-md-3" for="slug">Slug</label>
                    <input class="form-control col-xl-9 col-md-8" id="slug" name="slug" type="text" value="{{ old('slug', $blog->slug) }}" placeholder="Auto generated from title if empty">
                </div>

                <div class="form-group row">
                    <label class="col-xl-2 col-md-3" for="image">Image</label>
                    <div class="col-xl-9 col-md-8 p-0">
                        <input class="form-control" id="image" name="image" type="file" accept="image/*">
                        @if($blog->image_path)
                            <img class="mt-3" src="{{ \App\Support\PublicMedia::url($blog->image_path) }}" style="max-height:120px;" alt="{{ $blog->title }}">
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-xl-2 col-md-3" for="excerpt">Excerpt</label>
                    <textarea class="form-control col-xl-9 col-md-8" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>

                <div class="form-group row">
                    <label class="col-xl-2 col-md-3" for="content"><span class="text-danger">*</span> Content</label>
                    <textarea class="form-control col-xl-9 col-md-8" id="content" name="content" rows="12" required>{{ old('content', $blog->content) }}</textarea>
                </div>

                <div class="form-group row">
                    <label class="col-xl-2 col-md-3" for="author_name">Author</label>
                    <input class="form-control col-xl-9 col-md-8" id="author_name" name="author_name" type="text" value="{{ old('author_name', $blog->author_name ?: auth('admin')->user()?->name) }}">
                </div>

                <div class="form-group row">
                    <label class="col-xl-2 col-md-3" for="published_at">Published At</label>
                    <input class="form-control col-xl-9 col-md-8" id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', optional($blog->published_at)->format('Y-m-d\\TH:i')) }}">
                </div>

                <div class="form-group row">
                    <label class="col-xl-2 col-md-3">Status</label>
                    <div class="col-xl-9 col-md-8 p-0">
                        <label class="mb-0">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $blog->is_published))>
                            Published
                        </label>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </div>
        </div>
    </form>
@endsection
