@extends('backend.layouts.app')

@section('title', 'Blogs')
@section('page_title', 'Blogs')

@section('page_actions')
    <a class="btn btn-primary" href="{{ route('admin.blogs.create') }}">Create Blog</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordernone">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td>
                                @if($blog->image_path)
                                    <img class="table-img" src="{{ \App\Support\PublicMedia::url($blog->image_path) }}" alt="{{ $blog->title }}">
                                @endif
                            </td>
                            <td>
                                <strong>{{ $blog->title }}</strong><br>
                                <small>{{ $blog->slug }}</small>
                            </td>
                            <td>{{ $blog->author_name ?: 'Admin' }}</td>
                            <td>
                                <span class="badge badge-{{ $blog->is_published ? 'success' : 'warning' }}">
                                    {{ $blog->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $blog->published_at?->format('d M Y h:i A') ?: '-' }}</td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.blogs.edit', $blog) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Delete this blog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No blogs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $blogs->links() }}
        </div>
    </div>
@endsection
